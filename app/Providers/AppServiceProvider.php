<?php

namespace App\Providers;

use App\Http\Controllers\BotMan\Vk\QuotesController;
use App\Services\Bot\Vk\VkUsersService;
use App\Services\Messages\LaravelMessageService;
use App\Services\Messages\MessageService;
use App\UseCases\Bot\QuoteService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Foundation\Application;
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
    public function register(): void
    {
        $this->app->singleton(VkUsersService::class, static function (Application $app) {
            return new VkUsersService($app->make('botman'));
        });

        $this->app->when(QuotesController::class)
            ->needs(QuoteService::class)
            ->give(static function(Application $app) {
                return new QuoteService(
                    $app->make(VkUsersService::class),
                    $app->make(Filesystem::class)
                );
            });

        $this->app->singleton(MessageService::class, static function() {
            return new LaravelMessageService(null);
        });
    }
}
