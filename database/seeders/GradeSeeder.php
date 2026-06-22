<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\GradeConversion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GradeConversion::updateOrCreate([
            'origin_grade' => 'A',
            'internal_grade' => 'A',
        ]);

        GradeConversion::updateOrCreate([
            'origin_grade' => 'A-',
            'internal_grade' => 'AB',
        ]);

        GradeConversion::updateOrCreate([
            'origin_grade' => 'B',
            'internal_grade' => 'B',
        ]);

        GradeConversion::updateOrCreate([
            'origin_grade' => 'B-',
            'internal_grade' => 'BC',
        ]);

        GradeConversion::updateOrCreate([
            'origin_grade' => 'C',
            'internal_grade' => 'C',
        ]);

        GradeConversion::updateOrCreate([
            'origin_grade' => 'C-',
            'internal_grade' => 'CD',
        ]);

        GradeConversion::updateOrCreate([
            'origin_grade' => 'D',
            'internal_grade' => 'D',
        ]);

        GradeConversion::updateOrCreate([
            'origin_grade' => 'E',
            'internal_grade' => 'E',
        ]);
    }
}
