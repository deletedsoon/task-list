<?php

namespace Tests\Feature;

use App\Task;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use withFaker, RefreshDatabase;

    /**
     * Test guest cannot visit task list page.
     *
     * @return void
     */
    public function testGuestCannotVisitTaskList()
    {
        $response = $this->get('/tasks');

        $response->assertRedirect('/login');
        $response->assertStatus(302);
    }

    /**
     * Test user can visit task list page.
     *
     * @return void
     */
    public function testUserCanVisitTaskList()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/tasks');

        $response->assertSee($task->name);
        $response->assertStatus(200);
    }

    /**
     * Test guest cannot visit a task page.
     *
     * @return void
     */
    public function testGuestCannotVisitTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get('/tasks/'.$task->id);

        $response->assertRedirect('/login');
        $response->assertStatus(302);
    }

    /**
     * Test user can visit a task page.
     *
     * @return void
     */
    public function testUserCanVisitTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/tasks/'.$task->id);

        $response->assertSee($task->name);
        $response->assertStatus(200);
    }

    /**
     * Test guest cannot visit an edit task page.
     *
     * @return void
     */
    public function testGuestCannotVisitEditTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get('/tasks/'.$task->id.'/edit');

        $response->assertRedirect('/login');
        $response->assertStatus(302);
    }

    /**
     * Test user can visit an edit task page.
     *
     * @return void
     */
    public function testUserCanVisitEditTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->get('/tasks/'.$task->id.'/edit');

        $response->assertSee($task->name);
        $response->assertStatus(200);
    }

    /**
     * Test guest cannot store a task.
     *
     * @return void
     */
    public function testGuestCannotStoreTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->make([
            'user_id' => $user->id,
        ]);

        $response = $this->post('/tasks', [
                'name' => $task->name,
            ]);

        $this->assertDatabaseMissing('tasks', [
            'name' => $task->name,
        ]);
        $response->assertRedirect('/login');
        $response->assertStatus(302);
    }

    /**
     * Test user can store a task.
     *
     * @return void
     */
    public function testUserCanStoreTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->make();

        $response = $this->actingAs($user)
            ->post('/tasks', [
                'name' => $task->name,
            ]);

        $this->assertDatabaseHas('tasks', [
            'name' => $task->name,
        ]);
        $response->assertRedirect('/tasks');
        $response->assertStatus(302);
    }

    /**
     * Test guest cannot update a task.
     *
     * @return void
     */
    public function testGuestCannotUpdateTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $newTaskName = $this->faker->sentence(6, true);

        $response = $this->patch('/tasks/'.$task->id, [
            'name' => $newTaskName,
        ]);

        $this->assertDatabaseMissing('tasks', [
            'name' => $newTaskName,
        ]);
        $response->assertRedirect('/login');
        $response->assertStatus(302);
    }

    /**
     * Test guest can update a task.
     *
     * @return void
     */
    public function testUserCanUpdateTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $newTaskName = $this->faker->sentence(6, true);

        $response = $this->actingAs($user)
            ->patch('/tasks/'.$task->id, ['name' => $newTaskName]);

        $this->assertDatabaseHas('tasks', [
            'name' => $newTaskName,
        ]);
        $response->assertRedirect('/tasks/'.$task->id);
        $response->assertStatus(302);
    }

    /**
     * Test guest cannot delete a task.
     *
     * @return void
     */
    public function testGuestCannotDeleteTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->delete('/tasks/'.$task->id);

        $this->assertDatabaseHas('tasks', [
            'name' => $task->name,
        ]);
        $response->assertRedirect('/login');
        $response->assertStatus(302);
    }

    /**
     * Test user can delete a task.
     *
     * @return void
     */
    public function testUserCanDeleteTask()
    {
        $user = factory(User::class)->create();
        $task = factory(Task::class)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->delete('/tasks/'.$task->id);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
        $response->assertRedirect('/tasks');
        $response->assertStatus(302);
    }
}
