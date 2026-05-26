<?php

declare(strict_types=1);

namespace App\Domain\Task\DTO;

use App\Domain\Task\TaskPriority;
use App\Domain\Task\TaskStatus;
use App\Domain\Task\ValueObjects\ScheduledDate;
use App\Domain\Task\ValueObjects\ScheduledTime;
use App\Domain\Task\ValueObjects\TaskDescription;
use App\Domain\Task\ValueObjects\TaskTitle;

final readonly class CreateTaskInput
{
    public function __construct(
        public TaskTitle         $title,
        public ?TaskDescription  $description,
        public ScheduledDate     $scheduledDate,
        public ?ScheduledTime    $scheduledTime,
        public TaskStatus        $status,
        public TaskPriority      $priority,
    ) {}
}
