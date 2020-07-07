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
            return new Image([
                'binary' => "C:\Program Files\wkhtmltopdf\bin\wkhtmltoimage.exe",
                'width' => 0,
                'javascript-delay' => 700,
                'enable-javascript',
                'debug-javascript',
                'no-stop-slow-scripts',
                'transparent',
                'enable-smart-width',
                'ignoreWarnings' => true,
                'commandOptions' => [
                    'useExec' => true
                ]
            ]);
        });
    }
}
