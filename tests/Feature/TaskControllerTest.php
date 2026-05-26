<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_ok(): void
    {
        $response = $this->get(route('tasks.index'));

        $response->assertOk();
    }

    public function test_create_returns_ok(): void
    {
        $response = $this->get(route('tasks.create'));

        $response->assertOk();
    }

    public function test_store_creates_task_and_redirects(): void
    {
        $response = $this->post(route('tasks.store'), [
            'title'          => 'Nova tarefa',
            'description'    => null,
            'scheduled_date' => '2026-06-01',
            'scheduled_time' => null,
            'status'         => 'pending',
            'priority'       => 'medium',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['title' => 'Nova tarefa']);
    }

    public function test_store_fails_without_required_fields(): void
    {
        $response = $this->post(route('tasks.store'), []);

        $response->assertSessionHasErrors(['title', 'scheduled_date', 'status', 'priority']);
    }

    public function test_edit_returns_ok_for_existing_task(): void
    {
        $task = \App\Models\Task::create([
            'title'          => 'Tarefa existente',
            'scheduled_date' => '2026-06-01',
            'status'         => 'pending',
            'priority'       => 'medium',
        ]);

        $response = $this->get(route('tasks.edit', $task->id));

        $response->assertOk();
    }

    public function test_update_changes_task_and_redirects(): void
    {
        $task = \App\Models\Task::create([
            'title'          => 'Tarefa original',
            'scheduled_date' => '2026-06-01',
            'status'         => 'pending',
            'priority'       => 'medium',
        ]);

        $response = $this->put(route('tasks.update', $task->id), [
            'title'          => 'Tarefa atualizada',
            'scheduled_date' => '2026-06-01',
            'status'         => 'completed',
            'priority'       => 'high',
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', ['title' => 'Tarefa atualizada', 'status' => 'completed']);
    }

    public function test_destroy_deletes_task_and_redirects(): void
    {
        $task = \App\Models\Task::create([
            'title'          => 'Tarefa para excluir',
            'scheduled_date' => '2026-06-01',
            'status'         => 'pending',
            'priority'       => 'medium',
        ]);

        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_week_returns_ok(): void
    {
        $response = $this->get(route('tasks.week'));

        $response->assertOk();
    }
}
