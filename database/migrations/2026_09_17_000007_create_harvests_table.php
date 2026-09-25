<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harvests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planting_id')->constrained('plantings')->cascadeOnDelete();
            $table->date('harvest_date');
            $table->integer('total_yield_quantity');
            $table->decimal('total_yield_weight', 8, 2);
            $table->enum('quality_grade', ['Grade A', 'Grade B', 'Grade C']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harvests');
    }
};

