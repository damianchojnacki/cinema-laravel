<?php

namespace Feature\Http\Controllers;

use App\Models\Movie;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Http\Controllers\ControllerTestCase;

class ImageControllerTest extends ControllerTestCase
{
    public function test_returns_images_for_movie(): void
    {
        Storage::fake('local');

        $movie = Movie::factory()
            ->withFakeImages()
            ->create();

        if (! $movie->backdrop_path || ! $movie->poster_path) {
            $this->markTestIncomplete('No images available');
        }

        Storage::assertExists($movie->backdrop_path);
        Storage::assertExists($movie->poster_path);

        $this->get(route('images', ['path' => $movie->backdrop_path]))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg');

        $this->get(route('images', ['path' => $movie->poster_path]))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg');
    }

    public function test_converts_image_to_webp(): void
    {
        Storage::fake('local');

        $movie = Movie::factory()
            ->withFakeImages()
            ->create();

        $this->get(route('images', ['path' => $movie->backdrop_path, 'fm' => 'webp']))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/webp');
    }
}
