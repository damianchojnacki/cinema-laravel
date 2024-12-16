<?php

namespace Database\Factories;

use App\Models\Showing;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->email(),
            'showing_id' => Showing::factory(),
            'seats' => function (array $attributes) {
                $showing = Showing::findOrFail((int) $attributes['showing_id']);

                $seats = [];

                foreach (range(0, rand(1, 4)) as $i) {
                    $seats[] = [rand(0, $showing->rows), rand(0, $showing->columns)];
                }

                return $seats;
            },
            'total' => function (array $attributes) {
                return count($attributes['seats']) * 9;
            },
            'token' => Str::random(32),
        ];
    }
}
