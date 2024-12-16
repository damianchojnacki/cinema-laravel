<?php

namespace App\Services;

use Illuminate\Support\Facades\Facade;

/** @mixin TMDB */
class TMDBFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'tmdb';
    }
}
