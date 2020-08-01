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
    public function getUserWithPhoto100px($id): array
    {
        $user = $this->getVkUser($id, ['photo_100']);

        return [
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'photo' => $user['photo_100']
        ];
    }

    /**
     * @inheritDoc
     * @throws BadMethodCallException
     */
    public function getUser($id): array
    {
        $user = $this->getVkUser($id);

        return [
            'id' => $user['id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
        ];
    }

    /**
     * Returns vk user object as array (https://vk.com/dev/objects/user)
     *
     * @param int $id
     * @param array $fields additional fields
     *
     * @return array
     *
     * @throws BadMethodCallException
     */
    private function getVkUser($id, array $fields = []): array
    {
        $request = [
            'user_ids' => $id,
        ];

        if (!empty($fields)) {
            $request['fields'] = implode(',', $fields);
        }

        return  $this->botMan->sendRequest('users.get', $request)['response'][0];
    }
}