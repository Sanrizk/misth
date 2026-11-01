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
        $pimpinanRole = Role::where('name', 'pimpinan')->first()->id;
        $persediaanRole = Role::where('name', 'persediaan')->first()->id;
        $keuanganRole = Role::where('name', 'keuangan')->first()->id;

        // Admin
        User::create([
            'role_id' => $adminRole,
            'name' => 'Admin Misth',
            'email' => 'admin@misth.com',
            'password' => bcrypt('password'),
            'phone' => '081234567890'
        ]);

        // Pimpinan
        User::create([
            'role_id' => $pimpinanRole,
            'name' => 'Pimpinan Misth',
            'email' => 'pimpinan@misth.com',
            'password' => bcrypt('password'),
            'phone' => '081234567891'
        ]);

        // Persediaan
        User::create([
            'role_id' => $persediaanRole,
            'name' => 'Staf Persediaan',
            'email' => 'persediaan@misth.com',
            'password' => bcrypt('password'),
            'phone' => '081234567892'
        ]);

        // Keuangan
        User::create([
            'role_id' => $keuanganRole,
            'name' => 'Staf Keuangan',
            'email' => 'keuangan@misth.com',
            'password' => bcrypt('password'),
            'phone' => '081234567893'
        ]);

        // Petani
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'role_id' => $petaniRole,
                'name' => 'Petani ' . $i,
                'email' => "petani{$i}@misth.com",
                'password' => bcrypt('password'),
                'phone' => '08123456789' . ($i + 3)
            ]);
        }
    }
}
