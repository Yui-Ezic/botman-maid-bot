<?php


namespace App\Services\Bot\Vk;


use App\Services\Bot\ChatService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Core\BadMethodCallException;

class VkChatService implements ChatService
{
    /**
     * @var BotMan
     */
    private $botMan;

    /**
     * VkUsersService constructor.
     *
     * @param BotMan $botMan
     */
    public function __construct(BotMan $botMan)
    {
        $this->botMan = $botMan;
    }

    /**
     * @inheritDoc
     *
     * @throws BadMethodCallException
     */
    public function removeUser(int $chatId, int $userId): void
    {
        if ($chatId > 2000000000) {
            $chatId -= 2000000000;
        }

        $this->botMan->sendRequest('messages.removeChatUser', [
            'chat_id' => $chatId,
            'user_id' => $userId
        ]);
    }
}