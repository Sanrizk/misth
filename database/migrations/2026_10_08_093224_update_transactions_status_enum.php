<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First convert existing statuses to the new ones
        DB::table('transactions')->where('status', 'paid')->update(['status' => 'pending']);
        DB::table('transactions')->where('status', 'completed')->update(['status' => 'pending']);
        DB::table('transactions')->where('status', 'shipping')->update(['status' => 'pending']);
        DB::table('transactions')->where('status', 'cancelled')->update(['status' => 'batal']);
        
        // Then alter the column
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'terbayarkan', 'batal') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE transactions MODIFY COLUMN status ENUM('pending', 'paid', 'shipping', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
