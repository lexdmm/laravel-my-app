<?php

declare(strict_types=1);

namespace Tests\Fakes;

use App\Domain\Task\DTO\CreateTaskInput;
use App\Domain\Task\DTO\TaskFilters;
use App\Domain\Task\DTO\UpdateTaskInput;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Domain\Task\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as ConcretePaginator;
use Illuminate\Support\Collection;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    private array $tasks = [];
    private int   $nextId = 1;

    public function findOrFail(int $id): Task
    {
        return $this->tasks[$id] ?? throw new \RuntimeException("Task {$id} not found.");
    }

    public function getByDateRange(string $from, string $to): Collection
    {
        return collect($this->tasks)->filter(
            fn(Task $t) => $t->scheduledDate->toDateString() >= $from
                        && $t->scheduledDate->toDateString() <= $to
        )->values();
    }

    public function getFiltered(TaskFilters $filters): LengthAwarePaginator
    {
        $items = collect($this->tasks)->values();

        return new ConcretePaginator($items->all(), $items->count(), 15, 1);
    }

    public function create(CreateTaskInput $input): Task
    {
        $task = new Task(
            id:            $this->nextId++,
            title:         $input->title,
            description:   $input->description,
            scheduledDate: $input->scheduledDate,
            scheduledTime: $input->scheduledTime,
            status:        $input->status,
            priority:      $input->priority,
        );

        $this->tasks[$task->id] = $task;

        return $task;
    }

    public function update(Task $task, UpdateTaskInput $input): void
    {
        $this->tasks[$task->id] = new Task(
            id:            $task->id,
            title:         $input->title,
            description:   $input->description,
            scheduledDate: $input->scheduledDate,
            scheduledTime: $input->scheduledTime,
            status:        $input->status,
            priority:      $input->priority,
        );
    }

    public function delete(Task $task): void
    {
        unset($this->tasks[$task->id]);
    }

    public function all(): array
    {
        return $this->tasks;
    }
}
