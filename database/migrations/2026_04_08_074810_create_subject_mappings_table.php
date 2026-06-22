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
        Schema::create('subject_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('target_subject_id')->constrained('subjects')->onDelete('cascade');
            $table->timestamps();

            // Allow one-to-many by not adding a unique constraint on source_subject_id alone
            // but a unique constraint on the pair is good for data integrity.
            $table->unique(['source_subject_id', 'target_subject_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_mappings');
    }
};
