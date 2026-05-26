<?php

declare(strict_types=1);

namespace App\Domain\Task\Events;

use App\Domain\Task\DTO\UpdateTaskInput;
use App\Domain\Task\Task;

final readonly class TaskUpdated
{
    public function __construct(
        public Task            $task,
        public UpdateTaskInput $input,
    ) {}
}
