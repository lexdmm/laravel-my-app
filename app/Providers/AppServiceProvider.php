<?php

namespace App\Providers;

use App\Domain\Task\Events\TaskCreated;
use App\Domain\Task\Listeners\LogTaskCreated;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\EloquentTaskRepository;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TaskRepositoryInterface::class, EloquentTaskRepository::class);
    }

    public function boot(): void
    {
        Event::listen(TaskCreated::class, LogTaskCreated::class);
    }
}
