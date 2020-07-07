<?php


namespace App\Services\Bot\Vk;


use App\Services\Bot\UsersService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Core\BadMethodCallException;

class VkUsersService implements UsersService
{
    /**
     * @var BotMan
     */
    private $botMan;

    /**
     * VkUsersService constructor.
     * @param BotMan $botMan
     */
    public function __construct(BotMan $botMan)
    {
        $this->botMan = $botMan;
    }

    /**
     * @inheritDoc
     * @throws BadMethodCallException
     */
    public function getUserWithPhoto100px(int $id): array
    {
        $user = $this->botMan->sendRequest('users.get', [
            'user_ids' => $id,
            'fields' => 'photo_100'
        ]);

        return [
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'photo' => $user['photo_100']
        ];
    }
}