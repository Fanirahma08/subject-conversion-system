<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->string('decree_number')->nullable()->after('status');
            $table->date('decree_date')->nullable()->after('decree_number');
            $table->string('academic_year')->nullable()->after('decree_date');
            $table->string('rector_name')->nullable()->after('academic_year');
            $table->string('rector_nidn')->nullable()->after('rector_name');
            $table->integer('graduation_total_sks')->default(144)->after('rector_nidn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->dropColumn([
                'decree_number',
                'decree_date',
                'academic_year',
                'rector_name',
                'rector_nidn',
                'graduation_total_sks',
            ]);
        });
    }
};
