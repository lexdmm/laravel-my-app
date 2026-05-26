<?php

declare(strict_types=1);

namespace App\Domain\Task\ValueObjects;

use InvalidArgumentException;

final readonly class ScheduledDate
{
    private \DateTimeImmutable $date;

    public function __construct(string $date)
    {
        $parsed = \DateTimeImmutable::createFromFormat('Y-m-d', $date);

        if (!$parsed || $parsed->format('Y-m-d') !== $date) {
            throw new InvalidArgumentException("Data inválida: {$date}");
        }

        $this->date = $parsed;
    }

    public function format(string $format): string
    {
        return $this->date->format($format);
    }

    public function toDateString(): string
    {
        return $this->date->format('Y-m-d');
    }

    public function __toString(): string
    {
        return $this->toDateString();
    }
}
