<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string', 'max:2000'],
            'scheduled_date' => ['required', 'date'],
            'scheduled_time' => ['nullable', 'date_format:H:i'],
            'status'         => ['required', 'in:pending,in_progress,completed,cancelled'],
            'priority'       => ['required', 'in:low,medium,high'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'          => 'O título é obrigatório.',
            'scheduled_date.required' => 'A data de agendamento é obrigatória.',
            'scheduled_date.date'     => 'Data inválida.',
            'status.in'               => 'Status inválido.',
            'priority.in'             => 'Prioridade inválida.',
        ];
    }
}
