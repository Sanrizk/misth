<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
class UserSeeder extends Seeder {
    public function run(): void {
        User::truncate();
        $adminRole = Role::where('name', 'admin')->first()->id;
        $petaniRole = Role::where('name', 'petani')->first()->id;
        $customerRole = Role::where('name', 'customer')->first()->id;

        User::create([
            'role_id' => $adminRole,
            'name' => 'Admin',
            'email' => 'admin@hydrofarm.com',
            'password' => bcrypt('password'),
            'phone' => '081234567890'
        ]);

        for($i=1; $i<=3; $i++){
            User::create([
                'role_id' => $petaniRole,
                'name' => 'Petani ' . $i,
                'email' => "petani{$i}@hydrofarm.com",
                'password' => bcrypt('password'),
                'phone' => '08123456789' . $i
            ]);
        }

        User::factory()->count(10)->create(['role_id' => $customerRole]);
    }
}
