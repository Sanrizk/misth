<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('water_quality_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('planting_id')->constrained('plantings')->cascadeOnDelete();
            $table->timestamp('checked_at')->useCurrent();
            $table->decimal('ph_level', 3, 1);
            $table->integer('tds_ppm');
            $table->decimal('water_temp', 4, 1)->nullable();
            $table->text('notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_quality_logs');
    }
};

