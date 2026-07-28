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

        if (DB::getDriverName() === 'sqlite') {
            DB::statement("
                UPDATE subject_mappings
                SET prodi = (SELECT prodi FROM subjects WHERE subjects.id = subject_mappings.target_subject_id)
                WHERE prodi IS NULL
            ");
        } else {
            DB::statement("
                UPDATE subject_mappings sm
                JOIN subjects s ON sm.target_subject_id = s.id
                SET sm.prodi = s.prodi
                WHERE sm.prodi IS NULL
            ");
        }
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
