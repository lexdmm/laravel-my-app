<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domain\Task\Services\TaskService;
use App\Domain\Task\Services\WeeklyTaskService;
use App\Domain\Task\TaskPriority;
use App\Domain\Task\TaskStatus;
use App\Http\Requests\TaskRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService       $taskService,
        private readonly WeeklyTaskService $weeklyTaskService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'date_from', 'date_to']);

        return view('tasks.index', [
            'tasks'      => $this->taskService->list($filters),
            'statuses'   => TaskStatus::options(),
            'priorities' => TaskPriority::options(),
            'filters'    => $filters,
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
        $this->taskService->create($request->validated());

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
        $this->taskService->update($task, $request->validated());

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
        $data = $this->weeklyTaskService->getWeek($request->input('start', now()->toDateString()));

        return view('tasks.week', $data);
    }
}
