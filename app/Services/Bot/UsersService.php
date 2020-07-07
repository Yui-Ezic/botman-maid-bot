<?php


namespace App\Services\Bot;


interface UsersService
{
    /**
     * Returns user info as associated array with fields:
     * 'first_name', 'last_name', 'photo' (100x100px).
     * @param int $id
     * @return array
     */
    public function getUserWithPhoto100px(int $id): array;
}