<?php


namespace App\Services\Bot;


use App\Exceptions\Bot\Chat\PermissionDeniedToRemoveUser;
use App\Exceptions\Bot\Chat\UserHasAlreadyBeenRemoved;

interface ChatService
{
    /**
     * Kicks user from chat
     *
     * @param int $chatId
     * @param int $userId
     *
     * @return void
     *
     * @throws PermissionDeniedToRemoveUser
     * @throws UserHasAlreadyBeenRemoved
     */
    public function removeUser(int $chatId, int $userId): void;
}