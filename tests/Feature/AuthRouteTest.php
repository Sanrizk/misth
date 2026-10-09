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
        $role = Role::firstOrCreate(['name' => 'customer']);
        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_public_routes_accessible_to_guests()
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_guest_routes_accessible_to_guests()
    {
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
    }

    public function test_guest_routes_redirect_authenticated_users()
    {
        $user = $this->createUser();
        
        $this->actingAs($user)->get('/login')->assertStatus(302);
        $this->actingAs($user)->get('/register')->assertStatus(302);
    }

    public function test_logout_redirects()
    {
        $user = $this->createUser();
        $this->actingAs($user)->post('/logout')->assertStatus(302);
        $this->assertGuest();
    }
}
