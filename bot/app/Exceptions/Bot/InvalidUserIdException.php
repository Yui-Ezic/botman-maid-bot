<?php


namespace App\Exceptions\Bot;


use Exception;
use Throwable;

class InvalidUserIdException extends Exception
{
    private $id;

    public function __construct($message = "", $id = 0, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}