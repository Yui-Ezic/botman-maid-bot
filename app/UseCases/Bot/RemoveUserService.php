<?php


namespace App\UseCases\Bot;


use App\Services\Bot\ChatService;
use App\Services\Bot\UsersService;

class RemoveUserService
{
    /**
     * @var ChatService
     */
    private $chatService;

    /**
     * @var UsersService
     */
    private $usersService;

    /**
     * RemoveUserService constructor.
     *
     * @param ChatService $chatService
     * @param UsersService $usersService
     */
    public function __construct(ChatService $chatService, UsersService $usersService)
    {
        $this->chatService = $chatService;
        $this->usersService = $usersService;
    }

    /**
     * Removes user from chat
     *
     * @param string $chatId
     * @param string $userName
     */
    public function remove(string $chatId, string $userName): void
    {
        $userId = ltrim(parse_url($userName)['path'], '/');
        $user = $this->usersService->getUser($userId);

        $this->chatService->removeUser($chatId, $user['id']);
    }
}