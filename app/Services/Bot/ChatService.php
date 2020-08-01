<?php


namespace App\Services\Bot;


interface ChatService
{
    /**
     * Kicks user from chat
     *
     * @param int $chatId
     * @param int $userId
     *
     * @return void
     */
    public function removeUser(int $chatId, int $userId): void;
}