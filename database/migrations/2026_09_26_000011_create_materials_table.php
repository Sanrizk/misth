<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();         // Kode bahan: MAT-001
            $table->string('name', 100);                  // Nama bahan
            $table->enum('category', [
                'nutrient',       // Nutrisi/pupuk
                'pesticide',      // Pestisida
                'operational'     // Bahan operasional
            ]);
            $table->string('unit', 30);                   // Satuan: liter, kg, botol, pcs
            $table->decimal('stock', 10, 2)->default(0);  // Stok tersedia
            $table->decimal('min_stock', 10, 2)->default(0); // Batas minimum stok (untuk alert)
            $table->decimal('price_per_unit', 12, 2)->default(0); // Harga per satuan
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
