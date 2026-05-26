<?php

declare(strict_types=1);

namespace App\Domain\Task\ValueObjects;

use InvalidArgumentException;

final readonly class TaskDescription
{
    public function __construct(public readonly string $value)
    {
        if (mb_strlen($value) > 2000) {
            throw new InvalidArgumentException('A descrição não pode exceder 2000 caracteres.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
