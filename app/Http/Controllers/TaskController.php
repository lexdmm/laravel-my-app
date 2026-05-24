<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->date_to);
        }

        $tasks = $query->orderBy('scheduled_date')->orderBy('scheduled_time')->paginate(15)->withQueryString();

        return view('tasks.index', [
            'tasks'      => $tasks,
            'statuses'   => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
            'filters'    => $request->only(['status', 'date_from', 'date_to']),
        ]);
    }

    public function create(Request $request)
    {
        $task = new Task();

        if ($request->filled('date')) {
            $task->scheduled_date = Carbon::parse($request->input('date'));
        }

        return view('tasks.form', [
            'task'       => $task,
            'statuses'   => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
        ]);
    }

    public function store(TaskRequest $request)
    {
        Task::create($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso.');
    }

    public function edit(Task $task)
    {
        return view('tasks.form', [
            'task'       => $task,
            'statuses'   => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
        ]);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tarefa excluída com sucesso.');
    }

    public function week(Request $request)
    {
        $start = $request->filled('start')
            ? Carbon::parse($request->input('start'))->startOfWeek(Carbon::MONDAY)
            : Carbon::now()->startOfWeek(Carbon::MONDAY);

        $end = $start->copy()->endOfWeek(Carbon::SUNDAY);

        $tasks = Task::query()
            ->whereBetween('scheduled_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('scheduled_time')
            ->get()
            ->groupBy(fn (Task $task): string => $task->scheduled_date->toDateString());

        $days = collect();
        for ($day = $start->copy(); $day->lte($end); $day->addDay()) {
            $days->put($day->toDateString(), $day->copy());
        }

        return view('tasks.week', [
            'tasksByDay' => $tasks,
            'days'       => $days,
            'start'      => $start,
            'end'        => $end,
            'prevStart'  => $start->copy()->subWeek()->toDateString(),
            'nextStart'  => $start->copy()->addWeek()->toDateString(),
        ]);
    }
}
