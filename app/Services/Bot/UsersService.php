<?php


namespace App\Services\Bot;


interface UsersService
{
    /**
     * Returns user info as associated array with fields:
     * 'id', 'first_name', 'last_name', 'photo' (100x100px).
     * @param mixed $id user id
     * @return array
     */
    public function getUserWithPhoto100px($id): array;

    /**
     * Returns user info as associated array with fields:
     * 'id', first_name', 'last_name'.
     * @param mixed $id user id
     * @return array
     */
    public function getUser($id): array;
}