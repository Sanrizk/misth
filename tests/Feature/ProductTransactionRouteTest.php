<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class ProductTransactionRouteTest extends TestCase
{
    use RefreshDatabase;

    private function createUser($roleName)
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_product_routes_accessible_to_guests()
    {
        // Guest
        $this->get('/products')->assertRedirect('/login');
    }

    public function test_product_routes_accessible_to_authenticated_users()
    {
        $admin = $this->createUser('admin');
        $petani = $this->createUser('petani');

        // Admin has access
        $this->actingAs($admin)->get('/products')->assertStatus(200);

        // Petani is restricted (403)
        $this->actingAs($petani)->get('/products')->assertStatus(403);
    }
}
