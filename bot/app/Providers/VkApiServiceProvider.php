<?php


namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use VK\Client\VKApiClient;

class VkApiServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(VKApiClient::class, static function() {
            return new VKApiClient(config('botman.vk.version'));
        });
    }
}