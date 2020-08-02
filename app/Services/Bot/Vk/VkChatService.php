<?php


namespace App\Services\Bot\Vk;


use App\Exceptions\Bot\Chat\PermissionDeniedToRemoveUser;
use App\Exceptions\Bot\Chat\UserHasAlreadyBeenRemoved;
use App\Services\Bot\ChatService;
use VK\Client\VKApiClient;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class VkChatService implements ChatService
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
     * @param int $chatId
     * @param int $userId
     *
     * @throws VKApiException
     * @throws VKClientException
     */
    public function removeUser(int $chatId, int $userId): void
    {
        if ($chatId > 2000000000) {
            $chatId -= 2000000000;
        }

        try {
            $this->apiClient->messages()->removeChatUser($this->token, [
                'chat_id' => $chatId,
                'user_id' => $userId
            ]);
        } catch (VKApiException $e) {
            switch ($e->getCode()) {
                case 935:
                    throw new UserHasAlreadyBeenRemoved('User not found in chat.', $userId, $e);
                case 15:
                case 925:
                    throw new PermissionDeniedToRemoveUser('The chat must be an administrator.', $e->getCode(), $e);
            }
            throw $e;
        }
    }
}