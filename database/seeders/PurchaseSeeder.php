<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = \App\Models\Material::all();
        if ($materials->isEmpty()) {
            return;
        }
        
        \App\Models\Purchase::factory(10)->create()->each(function ($purchase) use ($materials) {
            $numItems = rand(2, 3);
            $total = 0;
            
            for ($i = 0; $i < $numItems; $i++) {
                $material = $materials->random();
                $quantity = rand(10, 50);
                $unitPrice = rand(1000, 50000);
                $subtotal = $quantity * $unitPrice;
                $total += $subtotal;
                
                \App\Models\PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'material_id' => $material->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);
            }
            
            $purchase->update([
                'total_amount' => $total,
            ]);
            
            // if received, update stock
            if ($purchase->status === 'received') {
                foreach ($purchase->purchaseItems as $item) {
                    $mat = $item->material;
                    $mat->stock += $item->quantity;
                    if ($mat->stock > 0 && $mat->status === 'inactive') {
                        $mat->status = 'active';
                    }
                    $mat->save();
                }
            }
        });
    }
}
