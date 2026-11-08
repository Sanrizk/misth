<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CashFlow;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        CashFlow::truncate();

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            PlantTypeSeeder::class,
            PlantingSeeder::class,
            MaterialSeeder::class,
            SupplierSeeder::class,
            PurchaseSeeder::class,
            MaintenanceLogSeeder::class,
            MaterialUsageSeeder::class,
            WaterQualityLogSeeder::class,
            HarvestSeeder::class,
            StockOpnameSeeder::class,
            CashFlowSeeder::class,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
