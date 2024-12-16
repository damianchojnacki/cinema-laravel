<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    protected bool $usesFakeImages = false;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(4),
            'release_date' => now()->subDays(rand(1, 30))->format('Y-m-d'),
            'rating' => fake()->randomFloat(1, 0, 10),
            'popularity' => fake()->numberBetween(0, 1000),
            'poster_path' => function (array $attributes) {
                if ($this->usesFakeImages) {
                    return null;
                }

                $image = file_get_contents('https://picsum.photos/600/800');

                Storage::put($path = ('posters/' . Str::slug($attributes['title']) . '.jpg'), $image ?: '');

                return $path;
            },
            'backdrop_path' => function (array $attributes) {
                if ($this->usesFakeImages) {
                    return null;
                }

                $image = file_get_contents('https://picsum.photos/1920/1080');

                Storage::put($path = ('backdrops/' . Str::slug($attributes['title']) . '.jpg'), $image ?: '');

                return $path;
            },
        ];
    }

    /**
     * @return Factory<Movie>
     */
    public function withFakeImages(): Factory
    {
        $this->usesFakeImages = true;

        return $this->state(function (array $attributes) {
            return [
                'poster_path' => function (array $attributes) {
                    $image = UploadedFile::fake()->image('poster.jpg')->getContent();

                    Storage::put($path = ('posters/' . Str::slug($attributes['title']) . '.jpg'), $image);

                    return $path;
                },
                'backdrop_path' => function (array $attributes) {
                    $image = UploadedFile::fake()->image('backdrop.jpg')->getContent();

                    Storage::put($path = ('backdrops/' . Str::slug($attributes['title']) . '.jpg'), $image);

                    return $path;
                },
            ];
        });
    }
}
