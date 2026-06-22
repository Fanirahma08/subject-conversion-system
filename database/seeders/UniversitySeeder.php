<?php

namespace Database\Seeders;

use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universities = [
            ['name' => 'Universitas Indonesia', 'code' => 'UI'],
            ['name' => 'Institut Teknologi Bandung', 'code' => 'ITB'],
            ['name' => 'Universitas Gadjah Mada', 'code' => 'UGM'],
            ['name' => 'Institut Teknologi Sepuluh Nopember', 'code' => 'ITS'],
            ['name' => 'Binus University', 'code' => 'BINUS'],
            ['name' => 'Politeknik Negeri Batam', 'code' => 'PNB'],
        ];

        foreach ($universities as $uni) {
            University::updateOrCreate(['name' => $uni['name']], $uni);
        }
    }
}
