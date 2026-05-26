<?php

declare(strict_types=1);

namespace App\Domain\Task;

enum TaskStatus: string
{
    case Pending    = 'pending';
    case InProgress = 'in_progress';
    case Completed  = 'completed';
    case Cancelled  = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Pending    => 'Pendente',
            self::InProgress => 'Em andamento',
            self::Completed  => 'Concluída',
            self::Cancelled  => 'Cancelada',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(fn(self $s) => ['value' => $s->value, 'label' => $s->label()], self::cases()),
            'label',
            'value'
        );
    }
}
