@extends('layouts.app')

@section('title', 'Nova tarefa')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 mb-4">Nova tarefa</h1>
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf
                        @include('tasks._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
