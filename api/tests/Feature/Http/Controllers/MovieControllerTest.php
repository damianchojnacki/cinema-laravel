<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\Api\MovieController;
use App\Models\Movie;
use Illuminate\Support\Facades\Storage;

class MovieControllerTest extends ControllerTestCase
{
    public function test_lists_movies(): void
    {
        Storage::fake('local');

        Movie::factory()
            ->withFakeImages()
            ->count($total = 20)
            ->create();

        $response = $this->getJson(route('movies.index'))
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'meta',
            ]);

        $this->assertCount(MovieController::$perPage, $response->json('data'));
        $this->assertEquals($total, $response->json('meta.total'));
    }

    public function test_shows_movie(): void
    {
        Storage::fake('local');

        /** @var Movie $movie */
        $movie = Movie::factory()
            ->withFakeImages()
            ->create();

        $this->getJson(route('movies.show', ['movie' => $movie]))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'description' => $movie->description,
                    'release_date' => $movie->release_date,
                    'rating' => $movie->rating,
                    'poster_url' => $movie->poster_url,
                    'backdrop_url' => $movie->backdrop_url,
                ],
            ]);
    }
}
