<?php

declare(strict_types=1);

namespace App\Domain\Task\Repositories;

use App\Domain\Task\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    public function findOrFail(int $id): Task;

    public function getByDateRange(string $from, string $to): Collection;

    public function getFiltered(array $filters): LengthAwarePaginator;

    public function create(array $data): Task;

    public function update(Task $task, array $data): void;

    public function delete(Task $task): void;
}
