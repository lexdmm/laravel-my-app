<?php

declare(strict_types=1);

namespace App\Domain\Task\Listeners;

use App\Domain\Task\Events\TaskCreated;
use Illuminate\Support\Facades\Log;

final class LogTaskCreated
{
    public function handle(TaskCreated $event): void
    {
        Log::info("Task criada: {$event->task->title->value}");
    }
}
