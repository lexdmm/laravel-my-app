@extends('layouts.app')

@section('title', 'Semana')

@section('content')

{{-- Cabeçalho da semana --}}
<div class="d-flex align-items-center justify-content-between mb-4">
    <a href="{{ route('tasks.week', ['start' => $prevStart]) }}"
       class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-chevron-left"></i> Semana anterior
    </a>

    <div class="text-center">
        <h5 class="mb-0 fw-semibold">
            {{ $start->translatedFormat('d \d\e F') }} — {{ $end->translatedFormat('d \d\e F \d\e Y') }}
        </h5>
        @if($start->isCurrentWeek())
            <span class="badge bg-primary mt-1">Semana atual</span>
        @endif
    </div>

    <a href="{{ route('tasks.week', ['start' => $nextStart]) }}"
       class="btn btn-outline-secondary btn-sm">
        Próxima semana <i class="bi bi-chevron-right"></i>
    </a>
</div>

{{-- Grade semanal --}}
<div class="row g-3">
    @foreach($days as $dateStr => $day)
        @php
            $dayTasks = $tasksByDay->get($dateStr, collect());
            $isToday  = $day->isToday();
        @endphp

        <div class="col-12 col-md-6 col-xl-3">
            <div class="card h-100 shadow-sm {{ $isToday ? 'border-primary' : '' }}">
                <div class="card-header py-2 {{ $isToday ? 'bg-primary text-white' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold text-capitalize">
                            {{ $day->translatedFormat('l') }}
                        </span>
                        <span class="small {{ $isToday ? 'text-white-50' : 'text-muted' }}">
                            {{ $day->format('d/m') }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-2">
                    @if($dayTasks->isEmpty())
                        <p class="text-muted small text-center mb-0 py-2">Nenhuma tarefa</p>
                    @else
                        @foreach($dayTasks as $task)
                            <div class="border rounded p-2 mb-2 bg-white">
                                <div class="d-flex justify-content-between align-items-start gap-1">
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="fw-semibold small text-truncate" title="{{ $task->title }}">
                                            {{ $task->title }}
                                        </div>
                                        @if($task->scheduled_time)
                                            <div class="text-muted" style="font-size: .75rem">
                                                <i class="bi bi-clock"></i>
                                                {{ substr($task->scheduled_time, 0, 5) }}
                                            </div>
                                        @endif
                                        <div class="mt-1 d-flex gap-1 flex-wrap">
                                            <span class="badge {{ $task->statusBadgeClass() }}" style="font-size: .7rem">
                                                {{ $task->status_label }}
                                            </span>
                                            <span class="badge {{ $task->priorityBadgeClass() }}" style="font-size: .7rem">
                                                {{ $task->priority_label }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-1 flex-shrink-0">
                                        <a href="{{ route('tasks.edit', $task) }}"
                                           class="btn btn-outline-secondary btn-sm p-0 px-1"
                                           title="Editar" style="font-size: .75rem">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                              onsubmit="return confirm('Excluir &quot;{{ addslashes($task->title) }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm p-0 px-1"
                                                    title="Excluir" style="font-size: .75rem">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="card-footer py-1 bg-transparent border-top-0 text-end">
                    <a href="{{ route('tasks.create', ['date' => $dateStr]) }}"
                       class="btn btn-link btn-sm p-0 text-decoration-none small">
                        <i class="bi bi-plus"></i> Adicionar
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection
