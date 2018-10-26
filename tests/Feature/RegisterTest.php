<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest can visit register page.
     *
     * @return void
     */
    public function testGuestCanVisitRegister()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * Test guest can register.
     *
     * @return void
     */
    public function testGuestCanRegister()
    {
        $user = factory(User::class)->make();

        $response = $this->post('/register', [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
        $response->assertRedirect('/tasks');
        $response->assertStatus(302);
    }

    /**
     * Test user cannot visit register page.
     *
     * @return void
     */
    public function testUserCannotVisitRegister()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->get('/login');

        $response->assertRedirect('/tasks');
        $response->assertStatus(302);
    }
}
