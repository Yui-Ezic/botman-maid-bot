<?php


namespace App\Services\Bot;


use App\Exceptions\Bot\Chat\PermissionDeniedToRemoveUser;
use App\Exceptions\Bot\Chat\UserHasAlreadyBeenRemoved;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

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

    /**
     * Checks or message came from chat
     *
     * @param IncomingMessage $message
     *
     * @return mixed
     */
    public function isChat(IncomingMessage $message): bool;

    /**
     * Checks if the user is an administrator
     *
     * @param int $chatId
     * @param int $userId
     *
     * @return bool
     */
    public function isUserAdmin(int $chatId, int $userId): bool;
}