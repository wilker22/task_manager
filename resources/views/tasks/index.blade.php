@extends('layouts.app')

@section('title', 'Minhas tarefas')

@php
    $statusBadge = ['pending' => 'secondary', 'in_progress' => 'info', 'done' => 'success'];
    $priorityBadge = ['low' => 'light text-dark border', 'medium' => 'warning text-dark', 'high' => 'danger'];
@endphp

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h1 class="h3 mb-0">Minhas tarefas</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nova tarefa
        </a>
    </div>

    <div class="row g-3 mb-4">
        @foreach ([['Total', $counts->sum(), '#4f46e5'], ['Pendentes', $counts['pending'] ?? 0, '#6c757d'], ['Em andamento', $counts['in_progress'] ?? 0, '#0dcaf0'], ['Concluídas', $counts['done'] ?? 0, '#198754']] as [$label, $value, $color])
            <div class="col-6 col-md-3">
                <div class="card stat-card shadow-sm" style="--accent: {{ $color }}">
                    <div class="card-body py-3">
                        <div class="text-muted small">{{ $label }}</div>
                        <div class="fs-4 fw-semibold">{{ $value }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="card shadow-sm border-0 mb-4">
        <div class="card-body row g-2 align-items-end">
            <div class="col-12 col-md-5">
                <label class="form-label small text-muted mb-1" for="q">Buscar</label>
                <input type="search" name="q" id="q" value="{{ request('q') }}" class="form-control" placeholder="Título ou descrição">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1" for="status">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Todos</option>
                    @foreach (\App\Models\Task::STATUSES as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small text-muted mb-1" for="priority">Prioridade</label>
                <select name="priority" id="priority" class="form-select">
                    <option value="">Todas</option>
                    @foreach (\App\Models\Task::PRIORITIES as $value => $label)
                        <option value="{{ $value }}" @selected(request('priority') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button class="btn btn-outline-primary flex-fill"><i class="bi bi-funnel"></i> Filtrar</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Limpar</a>
            </div>
        </div>
    </form>

    @if ($tasks->isEmpty())
        <div class="text-center text-muted py-5">
            <i class="bi bi-inbox" style="font-size: 3rem;"></i>
            <p class="mt-2 mb-3">Nenhuma tarefa encontrada.</p>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">Criar a primeira tarefa</a>
        </div>
    @else
        <div class="list-group shadow-sm mb-4">
            @foreach ($tasks as $task)
                <div class="list-group-item py-3 {{ $task->status === 'done' ? 'task-done' : '' }}">
                    <div class="d-flex gap-3 align-items-start">
                        <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-link p-0 fs-4 lh-1 {{ $task->status === 'done' ? 'text-success' : 'text-secondary' }}"
                                    title="{{ $task->status === 'done' ? 'Reabrir' : 'Concluir' }}">
                                <i class="bi {{ $task->status === 'done' ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                            </button>
                        </form>

                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="task-title fw-semibold">{{ $task->title }}</span>
                                <span class="badge bg-{{ $statusBadge[$task->status] }}">{{ $task->statusLabel() }}</span>
                                <span class="badge bg-{{ $priorityBadge[$task->priority] }}">{{ $task->priorityLabel() }}</span>
                            </div>
                            @if ($task->description)
                                <p class="text-muted small mb-1 mt-1">{{ \Illuminate\Support\Str::limit($task->description, 160) }}</p>
                            @endif
                            @if ($task->due_date)
                                <small class="{{ $task->isOverdue() ? 'text-danger fw-semibold' : 'text-muted' }}">
                                    <i class="bi bi-calendar-event"></i> {{ $task->due_date->format('d/m/Y') }}
                                    @if ($task->isOverdue()) · Atrasada @endif
                                </small>
                            @endif
                        </div>

                        <div class="d-flex gap-1">
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                  onsubmit="return confirm('Excluir esta tarefa?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Excluir"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $tasks->links() }}
    @endif
@endsection
