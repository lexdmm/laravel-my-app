@extends('layouts.app')

@section('title', $task ? 'Editar Tarefa' : 'Nova Tarefa')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="d-flex align-items-center mb-3 gap-2">
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h5 class="mb-0 fw-semibold">
                {{ $task ? 'Editar Tarefa' : 'Nova Tarefa' }}
            </h5>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form method="POST"
                      action="{{ $task ? route('tasks.update', $task->id) : route('tasks.store') }}">
                    @csrf
                    @if($task)
                        @method('PUT')
                    @endif

                    {{-- Título --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $task?->title->value) }}"
                               placeholder="Descreva a tarefa"
                               autofocus>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Descrição --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea id="description" name="description" rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Detalhes opcionais">{{ old('description', $task?->description?->value) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        {{-- Data --}}
                        <div class="col-sm-6">
                            <label for="scheduled_date" class="form-label">
                                Data <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="scheduled_date" name="scheduled_date"
                                   class="form-control @error('scheduled_date') is-invalid @enderror"
                                   value="{{ old('scheduled_date', $task?->scheduledDate->toDateString() ?? $scheduledDate) }}">
                            @error('scheduled_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Horário --}}
                        <div class="col-sm-6">
                            <label for="scheduled_time" class="form-label">Horário</label>
                            <input type="time" id="scheduled_time" name="scheduled_time"
                                   class="form-control @error('scheduled_time') is-invalid @enderror"
                                   value="{{ old('scheduled_time', $task?->scheduledTime?->formatted() ?? '') }}">
                            @error('scheduled_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        {{-- Status --}}
                        <div class="col-sm-6">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select id="status" name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}"
                                            @selected(old('status', $task?->status->value ?? 'pending') === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Prioridade --}}
                        <div class="col-sm-6">
                            <label for="priority" class="form-label">
                                Prioridade <span class="text-danger">*</span>
                            </label>
                            <select id="priority" name="priority"
                                    class="form-select @error('priority') is-invalid @enderror">
                                @foreach($priorities as $value => $label)
                                    <option value="{{ $value }}"
                                            @selected(old('priority', $task?->priority->value ?? 'medium') === $value)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i>
                            {{ $task ? 'Salvar Alterações' : 'Criar Tarefa' }}
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection
