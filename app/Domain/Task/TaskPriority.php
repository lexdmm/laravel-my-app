<?php

declare(strict_types=1);

namespace App\Domain\Task;

enum TaskPriority: string
{
    case Low    = 'low';
    case Medium = 'medium';
    case High   = 'high';

    public function label(): string
    {
        return match($this) {
            self::Low    => 'Baixa',
            self::Medium => 'Média',
            self::High   => 'Alta',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(fn(self $p) => ['value' => $p->value, 'label' => $p->label()], self::cases()),
            'label',
            'value'
        );
    }
}
