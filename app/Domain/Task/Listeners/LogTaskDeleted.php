<?php

declare(strict_types=1);

namespace App\Domain\Task\Listeners;

use App\Domain\Task\Events\TaskDeleted;
use Illuminate\Support\Facades\Log;

final class LogTaskDeleted
{
    public function handle(TaskDeleted $event): void
    {
        Log::info("Task excluída: {$event->task->title->value}");
    }
}
