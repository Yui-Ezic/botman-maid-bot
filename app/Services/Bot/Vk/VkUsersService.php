<?php


namespace App\Services\Bot\Vk;


use App\Exceptions\Bot\InvalidUserIdException;
use App\Services\Bot\UsersService;
use VK\Client\VKApiClient;
use VK\Exceptions\Api\VKApiParamUserIdException;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class VkUsersService implements UsersService
{
    /**
     * @var VKApiClient
     */
    private $apiClient;

    /**
     * @var string
     */
    private $token;

    /**
     * VkUsersService constructor.
     * @param VKApiClient $apiClient
     * @param string $token
     */
    public function __construct(VKApiClient $apiClient, string $token)
    {
        $this->apiClient = $apiClient;
        $this->token = $token;
    }

    /**
     * @inheritDoc
     *
     * @throws VKClientException
     * @throws VKApiException
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
     *
     * @throws VKClientException
     * @throws VKApiException
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
     * @throws VKClientException
     * @throws VKApiException
     * @throws InvalidUserIdException
     */
    private function getVkUser($id, array $fields = []): array
    {
        $postData = [
            'user_ids' => $id,
        ];

        if (!empty($fields)) {
            $postData['fields'] = implode(',', $fields);
        }

        try {
            $users = $this->apiClient->users()->get($this->token, $postData);
        } catch (VKApiParamUserIdException $e) {
            throw new InvalidUserIdException('Invalid user id', $id, $e);
        }
        return array_shift($users);
    }
}