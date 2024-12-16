<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Showing;
use Illuminate\Database\Seeder;

class ShowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Showing::factory()
            ->count(200)
            ->recycle(Movie::all())
            ->create();
    }
}
