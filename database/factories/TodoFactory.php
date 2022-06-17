<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'text' => ucfirst($this->faker->words(5, true)),
            'color_id' => $this->faker->numberBetween(0, 5),
            'completed' => $this->faker->numberBetween(0, 1),
        ];
    }
}
