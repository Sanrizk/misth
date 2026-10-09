<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class FarmRouteTest extends TestCase
{
    use RefreshDatabase;

    private function createUser($roleName)
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_farm_routes_authorization_for_guests()
    {
        $getRoutes = [
            '/plant-types',
            '/plantings',
            '/materials',
            '/suppliers',
            '/purchases',
            '/reports',
            '/reports/plantings',
            '/reports/harvests',
            '/reports/transactions',
            '/reports/materials',
        ];

        foreach ($getRoutes as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_farm_routes_authorization_for_customer()
    {
        $customer = $this->createUser('customer');

        $getRoutes = [
            '/plant-types',
            '/plantings',
            '/materials',
            '/suppliers',
            '/purchases',
            '/reports',
            '/reports/plantings',
            '/reports/harvests',
            '/reports/transactions',
            '/reports/materials',
        ];

        foreach ($getRoutes as $url) {
            $this->actingAs($customer)->get($url)->assertStatus(403);
            Auth()->logout();
        }
    }

    public function test_farm_routes_authorization_for_petani()
    {
        $petani = $this->createUser('petani');

        $getRoutes = [
            '/plant-types',
            '/plantings',
            '/materials',
            '/suppliers',
            '/purchases',
            '/reports',
            '/reports/plantings',
            '/reports/harvests',
            '/reports/transactions',
            '/reports/materials',
        ];

        foreach ($getRoutes as $url) {
            $this->actingAs($petani)->get($url)->assertStatus(200);
            Auth()->logout();
        }
    }

    public function test_farm_routes_authorization_for_admin()
    {
        $admin = $this->createUser('admin');

        $getRoutes = [
            '/plant-types',
            '/plantings',
            '/materials',
            '/suppliers',
            '/purchases',
            '/reports',
            '/reports/plantings',
            '/reports/harvests',
            '/reports/transactions',
            '/reports/materials',
        ];

        foreach ($getRoutes as $url) {
            $this->actingAs($admin)->get($url)->assertStatus(200);
            Auth()->logout();
        }
    }
}
