<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'pmb@gmail.com'],
            [
                'name' => 'PMB',
                'password' => Hash::make('password'),
                'role' => UserRole::PMB,
            ]
        );

        User::updateOrCreate(
            ['email' => 'kaprodi@gmail.com'],
            [
                'name' => 'Kaprodi',
                'password' => Hash::make('password'),
                'role' => UserRole::Kaprodi,
            ]
        );

        User::updateOrCreate(
            ['email' => 'baak@gmail.com'],
            [
                'name' => 'BAAK',
                'password' => Hash::make('password'),
                'role' => UserRole::BAAK,
            ]
        );

        User::updateOrCreate(
            ['email' => 'dekan@gmail.com'],
            [
                'name' => 'Dekan',
                'password' => Hash::make('password'),
                'role' => UserRole::Dekan,
            ]
        );

        User::updateOrCreate(
            ['email' => 'wr1@gmail.com'],
            [
                'name' => 'Wakil Rektor 1',
                'password' => Hash::make('password'),
                'role' => UserRole::WR1,
            ]
        );

        User::updateOrCreate(
            ['email' => 'rektor@gmail.com'],
            [
                'name' => 'Rektor',
                'password' => Hash::make('password'),
                'role' => UserRole::Rektor,
            ]
        );
    }
}
