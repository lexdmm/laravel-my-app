<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Task\Services;

use App\Domain\Task\DTO\CreateTaskInput;
use App\Domain\Task\DTO\TaskFilters;
use App\Domain\Task\DTO\UpdateTaskInput;
use App\Domain\Task\Events\TaskCreated;
use App\Domain\Task\Events\TaskDeleted;
use App\Domain\Task\Events\TaskUpdated;
use App\Domain\Task\Services\TaskService;
use App\Domain\Task\TaskPriority;
use App\Domain\Task\TaskStatus;
use App\Domain\Task\ValueObjects\ScheduledDate;
use App\Domain\Task\ValueObjects\TaskTitle;
use Illuminate\Support\Facades\Event;
use Tests\Fakes\InMemoryTaskRepository;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    private TaskService $service;
    private InMemoryTaskRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new InMemoryTaskRepository();
        $this->service    = new TaskService($this->repository);
    }

    public function test_create_persists_task_and_fires_event(): void
    {
        Event::fake();

        $input = new CreateTaskInput(
            title:         new TaskTitle('Estudar DDD'),
            description:   null,
            scheduledDate: new ScheduledDate('2026-06-01'),
            scheduledTime: null,
            status:        TaskStatus::Pending,
            priority:      TaskPriority::Medium,
        );

        $task = $this->service->create($input);

        $this->assertSame('Estudar DDD', $task->title->value);
        $this->assertSame(1, count($this->repository->all()));
        Event::assertDispatched(TaskCreated::class, fn($e) => $e->task->id === $task->id);
    }

    public function test_update_persists_changes_and_fires_event(): void
    {
        Event::fake();

        $createInput = new CreateTaskInput(
            title:         new TaskTitle('Tarefa original'),
            description:   null,
            scheduledDate: new ScheduledDate('2026-06-01'),
            scheduledTime: null,
            status:        TaskStatus::Pending,
            priority:      TaskPriority::Low,
        );

        $task = $this->service->create($createInput);

        $updateInput = new UpdateTaskInput(
            title:         new TaskTitle('Tarefa atualizada'),
            description:   null,
            scheduledDate: new ScheduledDate('2026-06-01'),
            scheduledTime: null,
            status:        TaskStatus::Completed,
            priority:      TaskPriority::High,
        );

        $this->service->update($task, $updateInput);

        $updated = $this->repository->findOrFail($task->id);

        $this->assertSame('Tarefa atualizada', $updated->title->value);
        $this->assertSame(TaskStatus::Completed, $updated->status);
        Event::assertDispatched(TaskUpdated::class);
    }

    public function test_delete_removes_task_and_fires_event(): void
    {
        Event::fake();

        $input = new CreateTaskInput(
            title:         new TaskTitle('Tarefa para excluir'),
            description:   null,
            scheduledDate: new ScheduledDate('2026-06-01'),
            scheduledTime: null,
            status:        TaskStatus::Pending,
            priority:      TaskPriority::Low,
        );

        $task = $this->service->create($input);
        $this->service->delete($task);

        $this->assertSame(0, count($this->repository->all()));
        Event::assertDispatched(TaskDeleted::class, fn($e) => $e->task->id === $task->id);
    }

    public function test_find_or_fail_throws_when_not_found(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->service->findOrFail(999);
    }

    public function test_list_returns_paginator(): void
    {
        $input = new CreateTaskInput(
            title:         new TaskTitle('Tarefa listada'),
            description:   null,
            scheduledDate: new ScheduledDate('2026-06-01'),
            scheduledTime: null,
            status:        TaskStatus::Pending,
            priority:      TaskPriority::Medium,
        );

        $this->service->create($input);

        $result = $this->service->list(new TaskFilters());

        $this->assertSame(1, $result->total());
    }
}
