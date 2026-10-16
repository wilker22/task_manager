<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $tasks = $user->tasks()
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->input('q').'%';
                $query->where(fn ($q) => $q->where('title', 'like', $term)->orWhere('description', 'like', $term));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->input('priority')))
            ->orderByRaw("FIELD(status, 'in_progress', 'pending', 'done')")
            ->orderByRaw('due_date IS NULL, due_date')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = $user->tasks()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('tasks.index', compact('tasks', 'counts'));
    }

    public function create(): View
    {
        return view('tasks.create', ['task' => new Task(['status' => 'pending', 'priority' => 'medium'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->user()->tasks()->create($this->validated($request));

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
    }

    public function edit(Task $task): View
    {
        Gate::authorize('update', $task);

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $task->update($this->validated($request));

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function toggle(Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $task->update(['status' => $task->status === 'done' ? 'pending' : 'done']);

        return back()->with('success', $task->status === 'done' ? 'Tarefa concluída!' : 'Tarefa reaberta.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarefa excluída.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(array_keys(Task::STATUSES))],
            'priority' => ['required', Rule::in(array_keys(Task::PRIORITIES))],
            'due_date' => ['nullable', 'date'],
        ]);
    }
}
