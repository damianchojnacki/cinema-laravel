<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //        if (app()->environment() === 'local') {
        //            Storage::disk('public')->deleteDirectory('backdrops');
        //            Storage::disk('public')->deleteDirectory('posters');
        //        }
        //
        //        $this->call(MovieSeeder::class);
        $this->call(ShowingSeeder::class);
        $this->call(ReservationSeeder::class);
    }
}
