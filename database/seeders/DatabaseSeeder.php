<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Order matters: Admins → Doctors → Nurses → Labs → Lab Tests → Patients
     * (Patients depend on Doctors and Nurses already being present.)
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            NurseSeeder::class,
        ]);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
