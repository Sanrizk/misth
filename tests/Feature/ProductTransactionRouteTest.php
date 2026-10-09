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

    public function test_product_and_transaction_routes_accessible_to_guests()
    {
        // Guest
        $this->get('/products')->assertRedirect('/login');
        $this->get('/transactions')->assertRedirect('/login');
    }

    public function test_product_and_transaction_routes_accessible_to_authenticated_users()
    {
        $roles = ['admin', 'petani', 'customer'];

        foreach ($roles as $roleName) {
            $user = $this->createUser($roleName);

            // Index routes should be 200 for everyone authenticated
            $this->actingAs($user)->get('/products')->assertStatus(200);
            $this->actingAs($user)->get('/transactions')->assertStatus(200);
            
            Auth()->logout();
        }
    }
}
