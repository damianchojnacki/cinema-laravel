<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Movie;
use App\Models\Reservation;
use App\Models\Showing;
use App\Notifications\ReservationPlaced;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ReservationControllerTest extends ControllerTestCase
{
    public function test_creates_reservation(): void
    {
        Notification::fake();

        Storage::fake('local');

        /** @var Movie $movie */
        $movie = Movie::factory()
            ->withFakeImages()
            ->create();

        /** @var Showing $showing */
        $showing = Showing::factory()
            ->for($movie)
            ->create();

        $response = $this->postJson(route('showings.reservations.store', ['showing' => $showing]), [
            'email' => $email = fake()->email(),
            'seats' => $seats = [[0, 0], [0, 1]],
        ])->assertCreated();

        $reservation = Reservation::find((int) $response->json('id'));

        $this->assertNotNull($reservation);

        $this->assertEquals($email, $reservation->email);
        $this->assertEquals($seats, $reservation->seats);

        Notification::assertSentOnDemand(ReservationPlaced::class);
    }

    public function test_can_not_create_reservation_for_taken_seats(): void
    {
        Notification::fake();

        Storage::fake('local');

        /** @var Movie $movie */
        $movie = Movie::factory()
            ->withFakeImages()
            ->create();

        /** @var Showing $showing */
        $showing = Showing::factory()
            ->for($movie)
            ->create();

        Reservation::factory()
            ->for($showing)
            ->create([
                'seats' => [[0, 1]],
            ]);

        $this->postJson(route('showings.reservations.store', ['showing' => $showing]), [
            'email' => fake()->email(),
            'seats' => [[0, 0], [0, 1]],
        ])->assertInvalid('seats');

        Notification::assertNothingSent();
    }

    public function test_shows_reservation_by_token(): void
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

        /** @var Reservation $reservation */
        $reservation = Reservation::factory()
            ->for($showing)
            ->create();

        $response = $this->getJson(route('reservations.show', ['reservation' => $reservation->token]))
            ->assertOk();

        $this->assertEquals($reservation->id, $response->json('data.id'));
        $this->assertEquals($reservation->email, $response->json('data.email'));
    }

    public function test_shows_reservation_qr_code(): void
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

        /** @var Reservation $reservation */
        $reservation = Reservation::factory()
            ->for($showing)
            ->create();

        $this->getJson(route('reservations.qr', ['reservation' => $reservation->token]))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml');
    }
}
