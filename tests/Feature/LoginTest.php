<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest can visit login page.
     *
     * @return void
     */
    public function testGuestCanVisitLogin()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * Test guest can login.
     *
     * @return void
     */
    public function testGuestCanLogin()
    {
        $user = factory(User::class)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertRedirect('/tasks');
        $response->assertStatus(302);
    }

    /**
     * Test logged in user cannot visit login page.
     *
     * @return void
     */
    public function testUserCannotVisitLogin()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->get('/login');

        $response->assertRedirect('/tasks');
        $response->assertStatus(302);
    }
}
