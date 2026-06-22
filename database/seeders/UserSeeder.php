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
            ['email' => 'dekan@gmail.com'],
            [
                'name' => 'Dekan',
                'password' => Hash::make('password'),
                'role' => UserRole::Dekan,
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
