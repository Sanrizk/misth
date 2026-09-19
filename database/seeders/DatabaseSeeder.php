<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PlantTypeSeeder::class,
            PlantingSeeder::class,
            MaintenanceLogSeeder::class,
            WaterQualityLogSeeder::class,
            HarvestSeeder::class,
            ProductSeeder::class,
            TransactionSeeder::class,
            TransactionDetailSeeder::class,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
