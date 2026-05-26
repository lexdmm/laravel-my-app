<?php

declare(strict_types=1);

namespace App\Domain\Task;

use App\Domain\Task\ValueObjects\ScheduledDate;
use App\Domain\Task\ValueObjects\ScheduledTime;
use App\Domain\Task\ValueObjects\TaskDescription;
use App\Domain\Task\ValueObjects\TaskTitle;

readonly class Task
{
    public function __construct(
        public ?int              $id,
        public TaskTitle         $title,
        public ?TaskDescription  $description,
        public ScheduledDate     $scheduledDate,
        public ?ScheduledTime    $scheduledTime,
        public TaskStatus        $status,
        public TaskPriority      $priority,
    ) {}
}
