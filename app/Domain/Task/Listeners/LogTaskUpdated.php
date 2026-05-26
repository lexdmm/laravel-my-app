<?php

declare(strict_types=1);

namespace App\Domain\Task\Listeners;

use App\Domain\Task\Events\TaskUpdated;
use Illuminate\Support\Facades\Log;

final class LogTaskUpdated
{
    public function handle(TaskUpdated $event): void
    {
        Log::info("Task atualizada: {$event->task->title->value}");
    }
}
