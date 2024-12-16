<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Showing>
 */
class ShowingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => Movie::factory(),
            'rows' => rand(4, 8),
            'columns' => rand(6, 10),
            'starts_at' => now()->addDays(rand(0, 5))->setHour(rand(8, 22))->setMinutes([0, 15, 30, 45][rand(0, 3)])->setSeconds(0),
        ];
    }
}
