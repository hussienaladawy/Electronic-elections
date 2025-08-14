<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SuperAdmin>
 */
class adminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
             return [
            
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('12345678'), // أو Hash::make
            'phone' => $this->faker->phoneNumber(),
            'national_id' => $this->faker->unique()->numberBetween(100000, 999999),
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
