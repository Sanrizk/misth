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
            '/reports/materials',
            '/stocks',
            '/finance',
        ];

        foreach ($getRoutes as $url) {
            $this->get($url)->assertRedirect('/login');
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
            '/reports/materials',
            '/stocks',
            '/finance',
        ];

        foreach ($getRoutes as $url) {
            $this->actingAs($admin)->get($url)->assertStatus(200);
            auth()->logout();
        }
    }

    public function test_farm_routes_authorization_for_petani()
    {
        $petani = $this->createUser('petani');

        // Petani bisa akses penanaman
        $this->actingAs($petani)->get('/plantings')->assertStatus(200);

        // Petani tidak bisa akses supplier, purchases, plant-types, materials, reports, finance
        $restrictedRoutes = [
            '/suppliers',
            '/purchases',
            '/plant-types',
            '/materials',
            '/reports',
            '/finance',
        ];

        foreach ($restrictedRoutes as $url) {
            $this->actingAs($petani)->get($url)->assertStatus(403);
            auth()->logout();
        }
    }

    public function test_farm_routes_authorization_for_persediaan()
    {
        $persediaan = $this->createUser('persediaan');

        // Persediaan bisa akses: suppliers, purchases, materials, stocks
        $allowedRoutes = [
            '/suppliers',
            '/purchases',
            '/materials',
            '/stocks',
        ];

        foreach ($allowedRoutes as $url) {
            $this->actingAs($persediaan)->get($url)->assertStatus(200);
            auth()->logout();
        }

        // Persediaan tidak bisa akses: plant-types, plantings, reports, finance
        $restrictedRoutes = [
            '/plant-types',
            '/plantings',
            '/reports',
            '/finance',
        ];

        foreach ($restrictedRoutes as $url) {
            $this->actingAs($persediaan)->get($url)->assertStatus(403);
            auth()->logout();
        }
    }

    public function test_farm_routes_authorization_for_keuangan()
    {
        $keuangan = $this->createUser('keuangan');

        // Keuangan bisa akses: finance
        $this->actingAs($keuangan)->get('/finance')->assertStatus(200);

        // Keuangan tidak bisa akses: plant-types, plantings, suppliers, purchases, materials, reports, stocks
        $restrictedRoutes = [
            '/plant-types',
            '/plantings',
            '/suppliers',
            '/purchases',
            '/materials',
            '/reports',
            '/stocks',
        ];

        foreach ($restrictedRoutes as $url) {
            $this->actingAs($keuangan)->get($url)->assertStatus(403);
            auth()->logout();
        }
    }

    public function test_farm_routes_authorization_for_pimpinan()
    {
        $pimpinan = $this->createUser('pimpinan');

        // Pimpinan bisa melihat: plantings, stocks, finance, reports
        $allowedRoutes = [
            '/plantings',
            '/stocks',
            '/finance',
            '/reports',
            '/reports/plantings',
            '/reports/harvests',
            '/reports/materials',
        ];

        foreach ($allowedRoutes as $url) {
            $this->actingAs($pimpinan)->get($url)->assertStatus(200);
            auth()->logout();
        }

        // Pimpinan tidak bisa akses: suppliers, purchases, materials, plant-types
        $restrictedRoutes = [
            '/suppliers',
            '/purchases',
            '/materials',
            '/plant-types',
        ];

        foreach ($restrictedRoutes as $url) {
            $this->actingAs($pimpinan)->get($url)->assertStatus(403);
            auth()->logout();
        }
    }
}