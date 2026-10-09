<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class ProfileDashboardRouteTest extends TestCase
{
    use RefreshDatabase;

    private function createUser($roleName = 'customer')
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_dashboard_and_profile_redirect_guests()
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_dashboard_accessible_to_admin_and_petani()
    {
        $admin = $this->createUser('admin');
        $this->actingAs($admin)->get('/dashboard')->assertStatus(200);

        Auth()->logout();

        $petani = $this->createUser('petani');
        $this->actingAs($petani)->get('/dashboard')->assertStatus(200);
    }

    public function test_dashboard_redirects_customer_to_store()
    {
        $customer = $this->createUser('customer');
        $this->actingAs($customer)->get('/dashboard')->assertRedirect(route('store.index'));
    }

    public function test_profile_accessible_to_all_authenticated_users()
    {
        $roles = ['admin', 'petani', 'customer'];

        foreach ($roles as $roleName) {
            $user = $this->createUser($roleName);
            $this->actingAs($user)->get('/profile')->assertStatus(200);
            Auth()->logout();
        }
    }
}
