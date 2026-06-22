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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('mahasiswa')->after('password');
            $table->string('prodi')->nullable()->after('role')->default('Sistem Informasi');
            $table->string('prodi_origin')->nullable()->after('prodi');
            $table->foreignId('university_id')->nullable()->after('prodi_origin')->constrained('universities')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropColumn(['role', 'prodi', 'prodi_origin', 'university_id']);
        });
    }
};
