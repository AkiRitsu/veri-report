<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * WARNING: This will delete all users and their associated reports (due to cascade delete).
     */
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Delete all users (reports will be cascade deleted)
        DB::table('users')->delete();
        
        // Reset auto-increment to 1
        // Note: MySQL may require the value to be at least the current max ID + 1
        // So we set it to 1 explicitly
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to reverse - data is already deleted
    }
};
