<?php


namespace App\Services\Bot\Vk;


use App\Services\Bot\UsersService;

class FakeVkUsersService implements UsersService
{

    /**
     * @inheritDoc
     */
    public function getUserWithPhoto100px(int $id): array
    {
        return [
            'first_name' => 'Yui',
            'last_name' => 'Ezic',
            'photo' => 'https://sun9-15.userapi.com/c857036/v857036068/16132b/8-aPD9XHfto.jpg?ava=1'
        ];
    }

    /**
     * @inheritDoc
     */
    public function getUser(int $id): array
    {
        return [
            'first_name' => 'Yui',
            'last_name' => 'Ezic',
        ];
    }
}