<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plantings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plant_type_id')->constrained('plant_types')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('batch_code', 50)->unique();
            $table->integer('quantity_seeds');
            $table->date('start_date');
            $table->enum('status', ['in_progress', 'harvested', 'failed']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plantings');
    }
};

