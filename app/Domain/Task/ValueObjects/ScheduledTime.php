<?php

declare(strict_types=1);

namespace App\Domain\Task\ValueObjects;

use InvalidArgumentException;

final readonly class ScheduledTime
{
    public function __construct(public readonly string $value)
    {
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $value)) {
            throw new InvalidArgumentException("Horário inválido: {$value}");
        }
    }

    public function formatted(): string
    {
        return substr($this->value, 0, 5);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
