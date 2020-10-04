<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use mikehaertl\wkhtmlto\Image;

class WkHtmlToImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(Image::class, function () {
            return new Image(config("wkhtmltoimage"));
        });
    }
}
