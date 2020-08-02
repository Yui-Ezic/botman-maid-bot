<?php


namespace App\Services\Bot;


use App\Exceptions\Bot\InvalidUserIdException;

interface UsersService
{
    /**
     * Returns user info as associated array with fields:
     * 'id', 'first_name', 'last_name', 'photo' (100x100px).
     *
     * @param mixed $id user id
     *
     * @return array
     *
     * @throws InvalidUserIdException
     */
    public function getUserWithPhoto100px($id): array;

    /**
     * Returns user info as associated array with fields:
     * 'id', first_name', 'last_name'.
     *
     * @param mixed $id user id
     *
     * @return array
     *
     * @throws InvalidUserIdException
     */
    public function getUser($id): array;
}