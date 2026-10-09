<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class AdminRouteTest extends TestCase
{
    use RefreshDatabase;

    private function createUser($roleName)
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_users_index_and_show_access()
    {
        $admin = $this->createUser('admin');
        $petani = $this->createUser('petani');
        $customer = $this->createUser('customer');
        
        $testUser = $this->createUser('customer');

        // Guest
        $this->get('/users')->assertRedirect('/login');
        $this->get('/users/' . $testUser->id)->assertRedirect('/login');

        // Customer (403)
        $this->actingAs($customer)->get('/users')->assertStatus(403);
        $this->actingAs($customer)->get('/users/' . $testUser->id)->assertStatus(403);

        // Petani (200)
        $this->actingAs($petani)->get('/users')->assertStatus(200);
        $this->actingAs($petani)->get('/users/' . $testUser->id)->assertStatus(200);

        // Admin (200)
        $this->actingAs($admin)->get('/users')->assertStatus(200);
        $this->actingAs($admin)->get('/users/' . $testUser->id)->assertStatus(200);
    }

    public function test_users_crud_access()
    {
        $admin = $this->createUser('admin');
        $petani = $this->createUser('petani');
        $customer = $this->createUser('customer');
        
        $testUser = $this->createUser('customer');

        $routes = [
            ['method' => 'get', 'url' => '/users/create'],
            ['method' => 'get', 'url' => '/users/' . $testUser->id . '/edit'],
            ['method' => 'post', 'url' => '/users'],
            ['method' => 'put', 'url' => '/users/' . $testUser->id],
            ['method' => 'delete', 'url' => '/users/' . $testUser->id],
        ];

        foreach ($routes as $route) {
            $method = $route['method'];
            $url = $route['url'];

            // Customer
            $this->actingAs($customer)->{$method}($url)->assertStatus(403);

            // Petani
            $this->actingAs($petani)->{$method}($url)->assertStatus(403);

            // Admin - should not be 403, might be 200, 302 (redirect), or validation errors 422, but not 403
            $response = $this->actingAs($admin)->{$method}($url);
            $this->assertNotEquals(403, $response->status(), "Admin received 403 on {$method} {$url}");
        }
    }
}
