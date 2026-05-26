<?php

declare(strict_types=1);

namespace App\Domain\Task\Services;

use App\Domain\Task\DTO\CreateTaskInput;
use App\Domain\Task\DTO\TaskFilters;
use App\Domain\Task\DTO\UpdateTaskInput;
use App\Domain\Task\Events\TaskCreated;
use App\Domain\Task\Events\TaskDeleted;
use App\Domain\Task\Events\TaskUpdated;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Domain\Task\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
    ) {}

    public function findOrFail(int $id): Task
    {
        return $this->tasks->findOrFail($id);
    }

    public function list(TaskFilters $filters): LengthAwarePaginator
    {
        return $this->tasks->getFiltered($filters);
    }

    public function create(CreateTaskInput $input): Task
    {
        $task = $this->tasks->create($input);

        event(new TaskCreated($task));

        return $task;
    }

    public function update(Task $task, UpdateTaskInput $input): void
    {
        $this->tasks->update($task, $input);

        event(new TaskUpdated($task, $input));
    }

    public function delete(Task $task): void
    {
        $this->tasks->delete($task);

        event(new TaskDeleted($task));
    }
}
