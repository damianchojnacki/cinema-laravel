<?php

namespace App\Console\Commands;

use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TMDB;

class ImportMovies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-movies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import movies from TMDB with backdrops and posters.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $movies = TMDB::movies();

        $this->output->info('Importing movies...');

        $this->withProgressBar($movies, function (array $movie) {
            Movie::create([
                'title' => $movie['title'],
                'description' => $movie['overview'],
                'release_date' => $movie['release_date'],
                'popularity' => $movie['popularity'],
                'rating' => $movie['vote_average'],
                'poster_path' => $this->savePoster($movie),
                'backdrop_path' => $this->saveBackdrop($movie),
            ]);
        });

        $this->newLine(2);

        $this->output->success('Movies successfully imported 🎉!');

        return 0;
    }

    /**
     * @param  array<string, mixed>  $movie
     */
    private function savePoster(array $movie): string
    {
        $image = TMDB::image($movie['poster_path']);

        Storage::put($path = ('posters/' . Str::slug($movie['title']) . '.jpg'), $image ?: '');

        return $path;
    }

    /**
     * @param  array<string, mixed>  $movie
     */
    private function saveBackdrop(array $movie): string
    {
        $image = TMDB::image($movie['backdrop_path']);

        Storage::put($path = ('backdrops/' . Str::slug($movie['title']) . '.jpg'), $image ?: '');

        return $path;
    }
}
