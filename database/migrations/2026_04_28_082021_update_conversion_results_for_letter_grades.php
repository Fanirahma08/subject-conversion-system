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
        Schema::table('conversion_results', function (Blueprint $table) {
            $table->string('origin_grade')->nullable()->after('target_subject_id');
            $table->dropColumn('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('conversion_results', function (Blueprint $table) {
            $table->dropColumn('origin_grade');
            $table->decimal('score', 4, 2)->nullable();
        });
    }
};
