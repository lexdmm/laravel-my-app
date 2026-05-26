<?php

declare(strict_types=1);

namespace App\Domain\Task\Events;

use App\Domain\Task\Task;

final readonly class TaskCreated
{
    public function __construct(
        public Task $task,
    ) {}
}
