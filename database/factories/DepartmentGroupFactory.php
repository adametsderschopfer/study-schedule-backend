<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DepartmentGroup>
 */
class DepartmentGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle,
            'degree' => $this->faker->randomDigit,
            'sub_group' => $this->faker->randomDigit,
            'year_of_education' => $this->faker->randomDigit,
            'form_of_education' => $this->faker->randomDigit,
        ];
    }
}
