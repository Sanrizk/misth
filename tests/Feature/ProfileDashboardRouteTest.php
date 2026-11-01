<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class ProfileDashboardRouteTest extends TestCase
{
    use RefreshDatabase;

    private function createUser($roleName)
    {
        $role = Role::firstOrCreate(['name' => $roleName]);
        return User::factory()->create(['role_id' => $role->id]);
    }

    public function test_dashboard_and_profile_redirect_guests()
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/profile')->assertRedirect('/login');
    }

    public function test_dashboard_accessible_to_all_roles()
    {
        $roles = ['admin', 'petani', 'pimpinan', 'persediaan', 'keuangan'];

        foreach ($roles as $roleName) {
            $user = $this->createUser($roleName);
            $this->actingAs($user)->get('/dashboard')->assertStatus(200);
            auth()->logout();
        }
    }

    public function test_profile_accessible_to_all_authenticated_users()
    {
        $roles = ['admin', 'petani', 'pimpinan', 'persediaan', 'keuangan'];

        foreach ($roles as $roleName) {
            $user = $this->createUser($roleName);
            $this->actingAs($user)->get('/profile')->assertStatus(200);
            auth()->logout();
        }
    }
}
