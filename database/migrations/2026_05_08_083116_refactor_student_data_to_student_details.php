<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add new columns to student_details
        Schema::table('student_details', function (Blueprint $table) {
            $table->foreignId('university_id')->nullable()->after('user_id')->constrained('universities')->nullOnDelete();
            $table->string('prodi_origin')->nullable()->after('university_id');
            $table->date('graduation_date')->nullable()->after('prodi_origin');
        });

        // 2. Ensure every student (role 'mahasiswa') has a student_details record
        $students = DB::table('users')->where('role', 'mahasiswa')->get();
        foreach ($students as $student) {
            DB::table('student_details')->updateOrInsert(
                ['user_id' => $student->id],
                [
                    'university_id' => $student->university_id,
                    'prodi_origin' => $student->prodi_origin,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 3. Drop columns from users
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropColumn(['university_id', 'prodi_origin']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Add columns back to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('prodi_origin')->nullable()->after('prodi');
            $table->foreignId('university_id')->nullable()->after('prodi_origin')->constrained('universities')->nullOnDelete();
        });

        // 2. Transfer data back
        $details = DB::table('student_details')->get();
        foreach ($details as $detail) {
            DB::table('users')->where('id', $detail->user_id)->update([
                'university_id' => $detail->university_id,
                'prodi_origin' => $detail->prodi_origin,
            ]);
        }

        // 3. Drop columns from student_details
        Schema::table('student_details', function (Blueprint $table) {
            $table->dropForeign(['university_id']);
            $table->dropColumn(['university_id', 'prodi_origin', 'graduation_date']);
        });
    }
};
