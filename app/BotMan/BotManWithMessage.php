<?php


namespace App\BotMan;


use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\CacheInterface;
use BotMan\BotMan\Interfaces\DriverInterface;
use BotMan\BotMan\Interfaces\StorageInterface;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

/**
 * Botman class which receives an IncomingMessage in constructor.
 *
 * We cannot use the original class in console applications or jobs because it does not
 * allow setting the message field dynamically or from constructor and keeps it empty.
 * (Usually it's filled in listen() method)
 *
 * @package App\BotMan
 */
class BotManWithMessage extends BotMan
{
    public function __construct(CacheInterface $cache, DriverInterface $driver, $config, StorageInterface $storage, IncomingMessage $message)
    {
        parent::__construct($cache, $driver, $config, $storage);
        $this->message = $message;
    }
}