<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class AuthRouteTest extends TestCase
{
    use RefreshDatabase;

    private function createUser()
    {
        $role = Role::firstOrCreate(['name' => 'petani']);
        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_guest_root_redirects_to_login()
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_authenticated_root_redirects_to_dashboard()
    {
        $user = $this->createUser();
        $this->actingAs($user)->get('/')->assertRedirect(route('dashboard'));
    }

    public function test_guest_routes_accessible_to_guests()
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_guest_routes_redirect_authenticated_users()
    {
        $user = $this->createUser();
        
        $this->actingAs($user)->get('/login')->assertStatus(302);
    }

    public function test_logout_redirects()
    {
        $user = $this->createUser();
        $this->actingAs($user)->post('/logout')->assertStatus(302);
        $this->assertGuest();
    }
}
