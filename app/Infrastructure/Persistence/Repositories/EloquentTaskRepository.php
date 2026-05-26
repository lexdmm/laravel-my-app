<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repositories;

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

    public function getFiltered(array $filters): LengthAwarePaginator
    {
        $query = TaskModel::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('scheduled_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('scheduled_date', '<=', $filters['date_to']);
        }

        $paginator = $query->orderBy('scheduled_date')->orderBy('scheduled_time')->paginate(15)->withQueryString();

        $paginator->through(TaskMapper::toEntity(...));

        return $paginator;
    }

    public function create(array $data): TaskEntity
    {
        return TaskMapper::toEntity(TaskModel::create($data));
    }

    public function update(TaskEntity $task, array $data): void
    {
        TaskModel::findOrFail($task->id)->update($data);
    }

    public function delete(TaskEntity $task): void
    {
        TaskModel::findOrFail($task->id)->delete();
    }
}
