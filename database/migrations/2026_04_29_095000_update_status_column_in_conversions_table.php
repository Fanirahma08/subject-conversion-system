<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE conversions MODIFY status ENUM('waiting', 'waiting_dekan', 'waiting_rektor', 'approved', 'rejected') DEFAULT 'waiting'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE conversions MODIFY status ENUM('waiting', 'approved', 'rejected') DEFAULT 'waiting'");
        }
    }
};
