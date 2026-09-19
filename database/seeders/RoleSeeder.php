<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Role;
class RoleSeeder extends Seeder {
    public function run(): void {
        Role::truncate();
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'petani']);
        Role::create(['name' => 'customer']);
    }
}
