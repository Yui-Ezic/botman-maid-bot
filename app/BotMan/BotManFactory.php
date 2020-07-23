<?php


namespace App\BotMan;


use BotMan\BotMan\Cache\LaravelCache;
use BotMan\BotMan\Interfaces\DriverInterface;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\Storages\Drivers\FileStorage;

/**
 * Factory for custom BotMan classes
 *
 * @package App\BotMan
 */
class BotManFactory
{
    /**
     * Creates BotmanWithMessage object
     *
     * @param DriverInterface $driver
     * @param IncomingMessage|null $message
     * @return BotManWithMessage
     */
    public static function createWithMessage(DriverInterface $driver, IncomingMessage $message = null): BotManWithMessage
    {
        if ($message === null) {
            $message = $driver->getMessages()[0];
        }

        return new BotManWithMessage(
            new LaravelCache(),
            $driver,
            config('botman', []),
            new FileStorage(storage_path('botman')),
            $message
        );
    }
}