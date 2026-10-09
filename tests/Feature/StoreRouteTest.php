<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;

class StoreRouteTest extends TestCase
{
    use RefreshDatabase;

    private function createUser($roleName)
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_store_index_accessible_to_all()
    {
        // Guest
        $this->get('/store')->assertStatus(200);

        // All Roles
        $roles = ['admin', 'petani', 'customer'];
        foreach ($roles as $roleName) {
            $user = $this->createUser($roleName);
            $this->actingAs($user)->get('/store')->assertStatus(200);
            Auth()->logout();
        }
    }

    public function test_customer_store_routes_authorization()
    {
        $getRoutes = [
            '/store/cart',
            '/store/checkout',
            '/store/orders',
        ];

        // Guest
        foreach ($getRoutes as $url) {
            $this->get($url)->assertRedirect('/login');
        }

        // Admin
        $admin = $this->createUser('admin');
        foreach ($getRoutes as $url) {
            $this->actingAs($admin)->get($url)->assertStatus(403);
            Auth()->logout();
        }

        // Petani
        $petani = $this->createUser('petani');
        foreach ($getRoutes as $url) {
            $this->actingAs($petani)->get($url)->assertStatus(403);
            Auth()->logout();
        }

        // Customer
        $customer = $this->createUser('customer');
        foreach ($getRoutes as $url) {
            $response = $this->actingAs($customer)->get($url);
            $this->assertNotEquals(403, $response->status());
            Auth()->logout();
        }
    }
}
