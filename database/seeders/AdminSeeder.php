<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admins = [
            [
                'name'  => 'Faisal Zayed',
                'email' => 'faisalzayed@gmail.com',
            ],
            [
                'name'  => 'Sara Al-Mansoori',
                'email' => 'sara.admin@hospital.com',
            ],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name'        => $admin['name'],
                    'password'    => Hash::make('password'),
                    'role'        => User::ROLE_ADMIN,
                    'is_verified' => true,
                ]
            );
        }
    }
}
