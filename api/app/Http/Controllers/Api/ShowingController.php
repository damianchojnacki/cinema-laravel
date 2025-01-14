<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShowingResource;
use App\Models\Movie;
use App\Models\Showing;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShowingController extends Controller
{
    /**
     * Get a listing of the resource.
     */
    public function index(Movie $movie): AnonymousResourceCollection
    {
        return ShowingResource::collection($movie->showings);
    }

    /**
     * Get the specified resource.
     */
    public function show(Movie $movie, Showing $showing): ShowingResource
    {
        $showing->load('reservations:showing_id,seats');

        return new ShowingResource($showing);
    }
}
