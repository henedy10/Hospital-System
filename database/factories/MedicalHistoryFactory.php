<?php

namespace Database\Factories;

use App\Models\MedicalHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicalHistoryFactory extends Factory
{
    protected $model = MedicalHistory::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'condition' => $this->faker->sentence(3),
            'diagnosis_date' => $this->faker->date(),
            'treatment' => $this->faker->paragraph(),
            'doctor_name' => $this->faker->name(),
        ];
    }
}
