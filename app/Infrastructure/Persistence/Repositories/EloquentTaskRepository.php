<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Task\DTO\CreateTaskInput;
use App\Domain\Task\DTO\TaskFilters;
use App\Domain\Task\DTO\UpdateTaskInput;
use App\Domain\Task\Repositories\TaskRepositoryInterface;
use App\Domain\Task\Task as TaskEntity;
use App\Infrastructure\Persistence\TaskMapper;
use App\Models\Task as TaskModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentTaskRepository implements TaskRepositoryInterface
{
    public function findOrFail(int $id): TaskEntity
    {
        return TaskMapper::toEntity(TaskModel::findOrFail($id));
    }

    public function getByDateRange(string $from, string $to): Collection
    {
        return TaskModel::query()
            ->whereBetween('scheduled_date', [$from, $to])
            ->orderBy('scheduled_time')
            ->get()
            ->map(TaskMapper::toEntity(...));
    }

    public function getFiltered(TaskFilters $filters): LengthAwarePaginator
    {
        $query = TaskModel::query();

        if ($filters->status !== null) {
            $query->where('status', $filters->status);
        }

        if ($filters->dateFrom !== null) {
            $query->whereDate('scheduled_date', '>=', $filters->dateFrom);
        }

        if ($filters->dateTo !== null) {
            $query->whereDate('scheduled_date', '<=', $filters->dateTo);
        }

        $paginator = $query->orderBy('scheduled_date')->orderBy('scheduled_time')->paginate(15)->withQueryString();

        $paginator->through(TaskMapper::toEntity(...));

        return $paginator;
    }

    public function create(CreateTaskInput $input): TaskEntity
    {
        return TaskMapper::toEntity(TaskModel::create([
            'title'          => $input->title->value,
            'description'    => $input->description?->value,
            'scheduled_date' => $input->scheduledDate->toDateString(),
            'scheduled_time' => $input->scheduledTime?->value,
            'status'         => $input->status->value,
            'priority'       => $input->priority->value,
        ]));
    }

    public function update(TaskEntity $task, UpdateTaskInput $input): void
    {
        TaskModel::findOrFail($task->id)->update([
            'title'          => $input->title->value,
            'description'    => $input->description?->value,
            'scheduled_date' => $input->scheduledDate->toDateString(),
            'scheduled_time' => $input->scheduledTime?->value,
            'status'         => $input->status->value,
            'priority'       => $input->priority->value,
        ]);
    }

    public function delete(TaskEntity $task): void
    {
        TaskModel::findOrFail($task->id)->delete();
    }
}
