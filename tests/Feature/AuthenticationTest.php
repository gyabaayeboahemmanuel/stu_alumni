<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlumniAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_alumni_can_authenticate_using_login_screen()
    {
        $user = User::factory()->create([
            'email' => 'alumni@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'alumni@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('alumni.dashboard'));
    }

    public function test_alumni_cannot_authenticate_with_invalid_password()
    {
        $user = User::factory()->create([
            'email' => 'alumni@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'alumni@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_alumni_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_admin_redirected_to_admin_dashboard()
    {
        $user = User::factory()->create([
            'email' => 'admin@stu.edu.gh',
            'password' => bcrypt('password123'),
        ]);

        $user->role_id = \App\Models\Role::where('name', \App\Models\Role::SUPER_ADMIN)->first()->id;
        $user->save();

        $response = $this->post('/login', [
            'email' => 'admin@stu.edu.gh',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('admin.dashboard'));
    }
}
