<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class MovieController extends Controller
{
    public static int $perPage = 12;

    /**
     * Display a listing of the resource.
     *
     * @response AnonymousResourceCollection<LengthAwarePaginator<MovieResource>>
     */
    public function index(): AnonymousResourceCollection
    {
        $movies = Movie::orderBy('popularity', 'desc')->paginate(static::$perPage);

        return MovieResource::collection($movies);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie): MovieResource
    {
        return new MovieResource($movie);
    }
}
