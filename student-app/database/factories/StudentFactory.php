<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $age = rand(15,20);
        return [
            'name' => fake()->name(),
            'image' => '',
            'age' => $age,
            'status' => fake()->randomElement($array = array('active','inactive'))
        ];
    }
}
