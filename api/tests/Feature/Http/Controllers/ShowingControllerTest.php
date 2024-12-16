<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Movie;
use App\Models\Reservation;
use App\Models\Showing;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class ShowingControllerTest extends ControllerTestCase
{
    public function test_lists_showings(): void
    {
        Storage::fake('local');

        /** @var Movie $movie */
        $movie = Movie::factory()
            ->withFakeImages()
            ->create();

        Showing::factory()
            ->for($movie)
            ->count($total = 3)
            ->create();

        $response = $this->getJson(route('movies.showings.index', ['movie' => $movie]))
            ->assertOk();

        $this->assertCount($total, $response->json('data'));
    }

    public function test_shows_showing(): void
    {
        Storage::fake('local');

        /** @var Movie $movie */
        $movie = Movie::factory()
            ->withFakeImages()
            ->create();

        /** @var Showing $showing */
        $showing = Showing::factory()
            ->for($movie)
            ->create();

        /** @var Collection<int, Reservation> $reservations */
        $reservations = Reservation::factory()
            ->for($showing)
            ->count(5)
            ->create();

        $response = $this->getJson(route('movies.showings.show', ['movie' => $movie, 'showing' => $showing]))
            ->assertOk();

        $this->assertEquals($showing->id, $response->json('data.id'));
        $this->assertCount(
            $reservations->sum(fn ($reservation) => count($reservation->seats)),
            $response->json('data.seats_taken'),
            'Seats taken does not match'
        );
    }
}
