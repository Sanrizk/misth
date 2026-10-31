<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('adjustment_number', 50)->unique();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('adjustment_date');
            $table->enum('type', ['in', 'out']); // in = penambahan fisik, out = pengurangan / kerusakan / susut
            $table->decimal('quantity', 10, 2);  // jumlah selisih
            $table->decimal('stock_before', 10, 2)->default(0);
            $table->decimal('stock_after', 10, 2)->default(0);
            $table->string('reason', 150)->nullable(); // contoh: Rusak, Kadaluarsa, Selisih Opname, Tumpah
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
