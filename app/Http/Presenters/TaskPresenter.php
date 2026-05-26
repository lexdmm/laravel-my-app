<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Domain\Task\TaskPriority;
use App\Domain\Task\TaskStatus;

final class TaskPresenter
{
    public static function statusBadgeClass(TaskStatus $status): string
    {
        return match($status) {
            TaskStatus::Pending    => 'bg-warning text-dark',
            TaskStatus::InProgress => 'bg-info text-dark',
            TaskStatus::Completed  => 'bg-success',
            TaskStatus::Cancelled  => 'bg-secondary',
        };
    }

    public static function priorityBadgeClass(TaskPriority $priority): string
    {
        return match($priority) {
            TaskPriority::Low    => 'bg-success',
            TaskPriority::Medium => 'bg-warning text-dark',
            TaskPriority::High   => 'bg-danger',
        };
    }
}
