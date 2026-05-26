<?php

declare(strict_types=1);

namespace App\Domain\Task\DTO;

final readonly class TaskFilters
{
    public function __construct(
        public ?string $status   = null,
        public ?string $dateFrom = null,
        public ?string $dateTo   = null,
    ) {}
}
