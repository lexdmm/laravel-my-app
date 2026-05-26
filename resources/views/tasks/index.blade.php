@extends('layouts.app')

@section('title', 'Tarefas')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-semibold">Todas as Tarefas</h5>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('tasks.index') }}" class="card card-body mb-4 shadow-sm">
    <div class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small mb-1">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Todos</option>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">De</label>
            <input type="date" name="date_from" class="form-control form-control-sm"
                   value="{{ $filters['date_from'] ?? '' }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Até</label>
            <input type="date" name="date_to" class="form-control form-control-sm"
                   value="{{ $filters['date_to'] ?? '' }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-secondary btn-sm">
                <i class="bi bi-funnel"></i> Filtrar
            </button>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                Limpar
            </a>
        </div>
    </div>
</form>

{{-- Lista --}}
@if($tasks->isEmpty())
    <div class="text-center text-muted py-5">
        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
        Nenhuma tarefa encontrada.
    </div>
@else
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle bg-white mb-0">
            <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Prioridade</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->title->value }}</td>
                        <td>{{ $task->scheduledDate->format('d/m/Y') }}</td>
                        <td>{{ $task->scheduledTime?->formatted() ?? '—' }}</td>
                        <td>
                            <span class="badge {{ \App\Http\Presenters\TaskPresenter::priorityBadgeClass($task->priority) }}">
                                {{ $task->priority->label() }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ \App\Http\Presenters\TaskPresenter::statusBadgeClass($task->status) }}">
                                {{ $task->status->label() }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('tasks.edit', $task->id) }}"
                               class="btn btn-outline-secondary btn-sm" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('tasks.destroy', $task->id) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Excluir a tarefa &quot;{{ addslashes($task->title->value) }}&quot;?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Excluir">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($tasks->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $tasks->links() }}
        </div>
    @endif
@endif

@endsection
