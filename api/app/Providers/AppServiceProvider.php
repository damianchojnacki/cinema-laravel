<?php

namespace App\Providers;

use App\Services\FrontendUrlGenerator;
use App\Services\TMDB;
use App\Services\TMDBFacade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        URL::macro('frontend', fn () => new FrontendUrlGenerator(config('app.frontend_url')));

        $this->registerTMDB();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
    }

    private function registerTMDB(): void
    {
        $this->app->singleton('tmdb', function () {
            return new TMDB(config('services.tmdb.api_key'));
        });

        $loader = AliasLoader::getInstance();

        $loader->alias('TMDB', TMDBFacade::class);
    }
}
