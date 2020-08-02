<?php


namespace App\Exceptions\Bot\Chat;


use Exception;
use Throwable;

class UserHasAlreadyBeenRemoved extends Exception
{
    private $userId;

    public function __construct($message = "", $userId = null, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }
}