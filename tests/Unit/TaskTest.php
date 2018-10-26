<?php

namespace Tests\Unit;

use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test a task is belongs to a user.
     *
     * @return void
     */
    public function testTaskBelongsToUser()
    {
        factory(User::class)->create();
        $task = factory(Task::class)->create();

        $this->assertInstanceOf('App\User', $task->user);
    }
}
