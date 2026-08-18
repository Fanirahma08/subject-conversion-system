<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('grade_conversions')) {
            // Update C- to map to C
            $exists = DB::table('grade_conversions')->where('origin_grade', 'C-')->exists();
            if ($exists) {
                DB::table('grade_conversions')->where('origin_grade', 'C-')->update(['internal_grade' => 'C']);
            } else {
                DB::table('grade_conversions')->insert([
                    'origin_grade' => 'C-',
                    'internal_grade' => 'C',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Remove any CD mappings as ITEBA does not have CD grades
            DB::table('grade_conversions')->where('internal_grade', 'CD')->delete();
            DB::table('grade_conversions')->where('origin_grade', 'CD')->delete();
        }

        if (Schema::hasTable('conversion_results')) {
            // Update any existing result records with CD grade to C
            DB::table('conversion_results')->where('grade', 'CD')->update(['grade' => 'C']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('grade_conversions')) {
            DB::table('grade_conversions')->where('origin_grade', 'C-')->update(['internal_grade' => 'CD']);
        }
    }
};
