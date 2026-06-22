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
            $table->foreignId('university_id')->nullable()->after('id')->constrained()->onDelete('cascade');
        });

        // Data Migration: Populate university_id from source_subject
        DB::statement("
            UPDATE subject_mappings sm
            JOIN subjects s ON sm.source_subject_id = s.id
            SET sm.university_id = s.university_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_mappings', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropColumn('university_id');
        });
    }
};
