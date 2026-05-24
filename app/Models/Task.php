<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'scheduled_date',
        'scheduled_time',
        'status',
        'priority',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    const STATUSES = [
        'pending'     => 'Pendente',
        'in_progress' => 'Em andamento',
        'completed'   => 'Concluída',
        'cancelled'   => 'Cancelada',
    ];

    const PRIORITIES = [
        'low'    => 'Baixa',
        'medium' => 'Média',
        'high'   => 'Alta',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getPriorityLabelAttribute(): string
    {
        return self::PRIORITIES[$this->priority] ?? $this->priority;
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending'     => 'bg-warning text-dark',
            'in_progress' => 'bg-info text-dark',
            'completed'   => 'bg-success',
            default       => 'bg-secondary',
        };
    }

    public function priorityBadgeClass(): string
    {
        return match ($this->priority) {
            'low'    => 'bg-success',
            'medium' => 'bg-warning text-dark',
            'high'   => 'bg-danger',
            default  => 'bg-secondary',
        };
    }
}
