<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->string('cash_number', 50)->unique();
            $table->date('transaction_date');
            $table->enum('type', ['in', 'out']); // in = pemasukan, out = pengeluaran
            $table->string('category', 100);    // Penjualan, Pembelian Bahan, Listrik, dsb
            $table->decimal('amount', 14, 2);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            // Penanda integrasi sistem otomatis
            $table->string('reference_type', 50)->default('manual'); // 'manual', 'order', 'purchase'
            $table->unsignedBigInteger('reference_id')->nullable();
            
            $table->text('description')->nullable();
            $table->timestamps();

            // Index untuk pencarian & filter cepat
            $table->index(['transaction_date', 'type']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};

