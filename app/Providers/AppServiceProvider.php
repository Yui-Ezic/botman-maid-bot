<?php

namespace App\Providers;

use App\Services\ProfanityFilter\BanBuilder;
use App\Services\ProfanityFilter\ProfanityFilter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProfanityFilter::class, static function() {
            return app(BanBuilder::class);
        });
    }
}
