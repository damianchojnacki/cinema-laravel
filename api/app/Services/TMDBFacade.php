<?php

namespace App\Services;

use Illuminate\Support\Facades\Facade;

class TMDBFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'tmdb';
    }
}