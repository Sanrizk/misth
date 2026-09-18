<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('harvest_id')->constrained('harvests')->cascadeOnDelete();
            $table->string('name', 100);
            $table->decimal('price', 12, 2);
            $table->integer('stock');
            $table->string('image_url')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'out_of_stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

