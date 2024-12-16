<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Showing;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reservation::factory()
            ->count(200)
            ->recycle(Showing::all())
            ->create();
    }
}
