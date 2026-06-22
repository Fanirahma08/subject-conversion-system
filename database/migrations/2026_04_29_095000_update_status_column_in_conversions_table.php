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
        DB::statement("ALTER TABLE conversions MODIFY status ENUM('waiting', 'waiting_dekan', 'waiting_rektor', 'approved', 'rejected') DEFAULT 'waiting'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Careful with down, if there are existing records with waiting_dekan/rektor, this might fail or truncate.
        DB::statement("ALTER TABLE conversions MODIFY status ENUM('waiting', 'approved', 'rejected') DEFAULT 'waiting'");
    }
};
