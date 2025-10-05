<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_can_be_created()
    {
        $response = $this->post('/tasks', [
            'title' => 'Test Task',
            'description' => 'Task description',
        ]);

        $response->assertRedirect('/tasks'); // redirects to index
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    public function test_task_can_be_toggled()
    {
        $task = Task::factory()->create(['is_completed' => 0]);

        $this->patch("/tasks/{$task->id}/toggle");
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'is_completed' => 1]);
    }
}
