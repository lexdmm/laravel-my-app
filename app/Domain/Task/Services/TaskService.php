<?php

declare(strict_types=1);

namespace App\Domain\Task\Services;

use App\Domain\Task\Events\TaskCreated;
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

    public function list(array $filters): LengthAwarePaginator
    {
        return $this->tasks->getFiltered($filters);
    }

    public function create(array $data): Task
    {
        $task = $this->tasks->create($data);

        event(new TaskCreated($task));

        return $task;
    }

    public function update(Task $task, array $data): void
    {
        $this->tasks->update($task, $data);
    }

    public function delete(Task $task): void
    {
        $this->tasks->delete($task);
    }
}
