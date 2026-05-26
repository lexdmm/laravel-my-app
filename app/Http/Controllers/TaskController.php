<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Task\DTO\CreateTaskInput;
use App\Domain\Task\DTO\TaskFilters;
use App\Domain\Task\DTO\UpdateTaskInput;
use App\Domain\Task\Services\TaskService;
use App\Domain\Task\Services\WeeklyTaskService;
use App\Domain\Task\TaskPriority;
use App\Domain\Task\TaskStatus;
use App\Domain\Task\ValueObjects\ScheduledDate;
use App\Domain\Task\ValueObjects\ScheduledTime;
use App\Domain\Task\ValueObjects\TaskDescription;
use App\Domain\Task\ValueObjects\TaskTitle;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController
{
    public function __construct(
        private readonly TaskService       $taskService,
        private readonly WeeklyTaskService $weeklyTaskService,
    ) {}

    public function index(Request $request): View
    {
        $filters = new TaskFilters(
            status:   $request->input('status') ?: null,
            dateFrom: $request->input('date_from') ?: null,
            dateTo:   $request->input('date_to') ?: null,
        );

        return view('tasks.index', [
            'tasks'      => $this->taskService->list($filters),
            'statuses'   => TaskStatus::options(),
            'priorities' => TaskPriority::options(),
            'filters'    => ['status' => $filters->status, 'date_from' => $filters->dateFrom, 'date_to' => $filters->dateTo],
        ]);
    }

    public function create(Request $request): View
    {
        return view('tasks.form', [
            'task'          => null,
            'scheduledDate' => $request->input('date'),
            'statuses'      => TaskStatus::options(),
            'priorities'    => TaskPriority::options(),
        ]);
    }

    public function store(TaskRequest $request): RedirectResponse
    {
        $this->taskService->create($this->buildCreateInput($request));

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso.');
    }

    public function edit(int $id): View
    {
        $task = $this->taskService->findOrFail($id);

        return view('tasks.form', [
            'task'          => $task,
            'scheduledDate' => $task->scheduledDate->toDateString(),
            'statuses'      => TaskStatus::options(),
            'priorities'    => TaskPriority::options(),
        ]);
    }

    public function update(TaskRequest $request, int $id): RedirectResponse
    {
        $task = $this->taskService->findOrFail($id);
        $this->taskService->update($task, $this->buildUpdateInput($request));

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $task = $this->taskService->findOrFail($id);
        $this->taskService->delete($task);

        return redirect()->route('tasks.index')->with('success', 'Tarefa excluída com sucesso.');
    }

    public function week(Request $request): View
    {
        return view('tasks.week', $this->weeklyTaskService->getWeek(
            $request->input('start', now()->toDateString())
        ));
    }

    private function buildCreateInput(TaskRequest $request): CreateTaskInput
    {
        return new CreateTaskInput(
            title:         new TaskTitle($request->input('title')),
            description:   $request->filled('description') ? new TaskDescription($request->input('description')) : null,
            scheduledDate: new ScheduledDate($request->input('scheduled_date')),
            scheduledTime: $request->filled('scheduled_time') ? new ScheduledTime($request->input('scheduled_time')) : null,
            status:        TaskStatus::from($request->input('status')),
            priority:      TaskPriority::from($request->input('priority')),
        );
    }

    private function buildUpdateInput(TaskRequest $request): UpdateTaskInput
    {
        return new UpdateTaskInput(
            title:         new TaskTitle($request->input('title')),
            description:   $request->filled('description') ? new TaskDescription($request->input('description')) : null,
            scheduledDate: new ScheduledDate($request->input('scheduled_date')),
            scheduledTime: $request->filled('scheduled_time') ? new ScheduledTime($request->input('scheduled_time')) : null,
            status:        TaskStatus::from($request->input('status')),
            priority:      TaskPriority::from($request->input('priority')),
        );
    }
}
