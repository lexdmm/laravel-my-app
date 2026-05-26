<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Task\Task;
use App\Domain\Task\TaskPriority;
use App\Domain\Task\TaskStatus;
use App\Domain\Task\ValueObjects\ScheduledDate;
use App\Domain\Task\ValueObjects\ScheduledTime;
use App\Domain\Task\ValueObjects\TaskDescription;
use App\Domain\Task\ValueObjects\TaskTitle;
use App\Models\Task as TaskModel;

final class TaskMapper
{
    public static function toEntity(TaskModel $model): Task
    {
        return new Task(
            id:            $model->id,
            title:         new TaskTitle($model->title),
            description:   $model->description ? new TaskDescription($model->description) : null,
            scheduledDate: new ScheduledDate($model->scheduled_date->toDateString()),
            scheduledTime: $model->scheduled_time ? new ScheduledTime($model->scheduled_time) : null,
            status:        TaskStatus::from($model->status),
            priority:      TaskPriority::from($model->priority),
        );
    }
}
