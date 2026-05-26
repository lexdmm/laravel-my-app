<?php

declare(strict_types=1);

namespace App\Domain\Task\Repositories;

use App\Domain\Task\DTO\CreateTaskInput;
use App\Domain\Task\DTO\TaskFilters;
use App\Domain\Task\DTO\UpdateTaskInput;
use App\Domain\Task\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function findOrFail(int $id): Task;

    public function getByDateRange(string $from, string $to): Collection;

    public function getFiltered(TaskFilters $filters): LengthAwarePaginator;

    public function create(CreateTaskInput $input): Task;

    public function update(Task $task, UpdateTaskInput $input): void;

    public function delete(Task $task): void;
}
