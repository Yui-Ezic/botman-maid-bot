<?php

namespace App\Providers;

use App\Services\Bot\UsersService;
use App\Services\Bot\Vk\FakeVkUsersService;
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
    }
}
