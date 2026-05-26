<?php

declare(strict_types=1);

namespace App\Domain\Task\Services;

use App\Domain\Task\Repositories\TaskRepositoryInterface;
use Illuminate\Support\Carbon;

class WeeklyTaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $tasks,
    ) {}

    public function getWeek(string $startDate): array
    {
        $start = Carbon::parse($startDate)->startOfWeek(Carbon::MONDAY);
        $end   = $start->copy()->endOfWeek(Carbon::SUNDAY);

        $tasksByDay = $this->tasks
            ->getByDateRange($start->toDateString(), $end->toDateString())
            ->groupBy(fn($task): string => $task->scheduledDate->toDateString());

        $days = collect();
        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $days->put($day->toDateString(), $day->copy());
        }

        return [
            'tasksByDay' => $tasksByDay,
            'days'       => $days,
            'start'      => $start,
            'end'        => $end,
            'prevStart'  => $start->copy()->subWeek()->toDateString(),
            'nextStart'  => $start->copy()->addWeek()->toDateString(),
        ];
    }
}
