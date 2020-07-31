<?php

namespace App\Providers;

use App\Services\Bot\MessageCreator;
use App\Services\Bot\UsersService;
use App\Services\Bot\Vk\VkMessageCreator;
use App\Services\Bot\Vk\VkUsersService;
use App\Services\Images\ImagickTrimmer;
use App\Services\Messages\LaravelMessageService;
use App\Services\Messages\MessageService;
use App\Services\Quotes\QuotesMaker;
use App\UseCases\Bot\QuoteService;
use DomainException;
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

        $this->app->bind(UsersService::class, static function(Application $app) {
            $botman = $app->make('botman');
            switch ($botman->getDriver()->getName())
            {
                case 'VkCommunityCallback':
                    return $app->make(VkUsersService::class);
                    break;
                default:
                    throw new DomainException('Unsupported driver: ' . $botman->getDriver()->getName());
            }
        });

        $this->app->bind(MessageCreator::class, static function (Application $app) {
            $botman = $app->make('botman');
            switch ($botman->getDriver()->getName())
            {
                case 'VkCommunityCallback':
                    return $app->make(VkMessageCreator::class);
                    break;
                default:
                    throw new DomainException('Unsupported driver: ' . $botman->getDriver()->getName());
            }
        });

        $this->app->bind(QuoteService::class, static function(Application $app) {
                $botman = $app->make('botman');
                switch ($botman->getDriver()->getName())
                {
                    case 'VkCommunityCallback':
                        $userService = $app->make(VkUsersService::class);
                        break;
                    default:
                        throw new DomainException('Unsupported driver: ' . $botman->getDriver()->getName());
                }
                return new QuoteService(
                    $userService,
                    $app->make(Filesystem::class),
                    $app->make(ImagickTrimmer::class),
                    $app->make(QuotesMaker::class)
                );
            });

        $this->app->singleton(MessageService::class, static function() {
            return new LaravelMessageService(null);
        });
    }
}
