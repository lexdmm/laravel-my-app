<?php

declare(strict_types=1);

namespace App\Domain\Task\ValueObjects;

use InvalidArgumentException;

final readonly class TaskTitle
{
    public function __construct(public readonly string $value)
    {
        $trimmed = trim($value);

        if ($trimmed === '') {
            throw new InvalidArgumentException('O título não pode ser vazio.');
        }

        if (mb_strlen($trimmed) > 255) {
            throw new InvalidArgumentException('O título não pode exceder 255 caracteres.');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
