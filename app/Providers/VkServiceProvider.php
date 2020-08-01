<?php


namespace App\Providers;


use App\Services\Bot\Vk\VkChatService;
use App\Services\Bot\Vk\VkUsersService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class VkServiceProvider extends ServiceProvider
{
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

        $this->app->singleton(VkChatService::class, static function (Application $app) {
            return new VkChatService($app->make('botman'));
        });
    }
}