<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('subject_mappings', function (Blueprint $table) {
            $table->string('prodi')->nullable()->after('university_id')->index();
        });

        // Data Migration: Populate prodi from target_subject
        DB::statement("
            UPDATE subject_mappings sm
            JOIN subjects s ON sm.target_subject_id = s.id
            SET sm.prodi = s.prodi
            WHERE sm.prodi IS NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_mappings', function (Blueprint $table) {
            $table->dropColumn('prodi');
        });
    }
};
