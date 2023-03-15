<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'day_of_week' => $this->faker->numberBetween($min = 1, $max = 7),
            'repeatability' => $this->faker->numberBetween($min = 0, $max = 4),
            'type' => $this->faker->randomDigit, 
            'sub_group' => $this->faker->randomDigit,
            'repeat_start' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
            'repeat_end' => $this->faker->date($format = 'Y-m-d', $max = 'now'),
        ];
    }
}
