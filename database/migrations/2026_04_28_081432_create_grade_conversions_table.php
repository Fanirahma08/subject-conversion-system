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
        Schema::create('grade_conversions', function (Blueprint $table) {
            $table->id();
            $table->string('origin_grade');
            $table->string('internal_grade');
            $table->timestamps();

            $table->unique('origin_grade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_conversions');
    }
};
