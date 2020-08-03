<?php


namespace App\Exceptions\Bot;


use BotMan\BotMan\Interfaces\DriverInterface;
use Exception;
use Throwable;

class UnsupportedDriverException extends Exception
{
    /**
     * @var DriverInterface|null
     */
    private $driver;

    public function __construct($message = "", DriverInterface $driver = null, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->driver = $driver;
    }

    /**
     * @return DriverInterface|null
     */
    public function getDriver(): ?DriverInterface
    {
        return $this->driver;
    }
}