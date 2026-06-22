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
        Schema::create('conversion_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversion_id')->constrained('conversions')->cascadeOnDelete();
            $table->foreignId('source_subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->foreignId('target_subject_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('grade')->nullable();
            $table->decimal('score', 4, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_results');
    }
};
