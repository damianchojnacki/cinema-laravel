<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class TMDB
{
    protected static string $baseUrl = 'https://api.themoviedb.org/3/';

    protected static string $imageUrl = 'https://image.tmdb.org/t/p/';

    public function __construct(protected string $apiKey) {}

    public function request(): PendingRequest
    {
        return Http::acceptJson()
            ->baseUrl(static::$baseUrl)
            ->withToken($this->apiKey);
    }

    /**
     * @return \Illuminate\Support\Collection<int, array<string, mixed>>
     *
     * @throws ConnectionException
     */
    public function movies(): Collection
    {
        return $this->request()
            ->get('discover/movie?sort_by=popularity.desc')
            ->collect('results');
    }

    public function image(string $path, string $size = 'original'): string|false
    {
        return file_get_contents(static::$imageUrl . "$size/$path");
    }
}
