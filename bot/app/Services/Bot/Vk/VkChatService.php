<?php


namespace App\Services\Bot\Vk;


use App\Exceptions\Bot\Chat\PermissionDeniedToRemoveUser;
use App\Exceptions\Bot\Chat\UserHasAlreadyBeenRemoved;
use App\Services\Bot\ChatService;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
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

    /**
     * @inheritDoc
     */
    public function isChat(IncomingMessage $message): bool
    {
        return $message->getRecipient() > 2000000000;
    }

    /**
     * @inheritDoc
     *
     * @throws VKApiException
     * @throws VKClientException
     */
    public function isUserAdmin(int $chatId, int $userId): bool
    {
        $admins = $this->getAdministrators($chatId);

        return in_array($userId, array_map(static function ($admin) {
            return $admin['member_id'];
        }, $admins), true);
    }

    /**
     * @param int $chatId
     *
     * @return array
     *
     * @throws VKApiException
     * @throws VKClientException
     */
    private function getAdministrators(int $chatId): array
    {
        $members = $this->apiClient->messages()->getConversationMembers($this->token, [
            'peer_id' => $chatId
        ])['items'];

        return array_filter($members, static function ($member) {
            return array_key_exists('is_admin', $member) && $member['is_admin'];
        });
    }

    /**
     * @inheritDoc
     */
    public function getAdminsList(int $chatId): array
    {
        $allAdmins = array_map(static function ($member) {
            return $member['member_id'];
        }, $this->getAdministrators($chatId));

        return array_filter($allAdmins, static function ($id) {
            /*
             * Remove all bots and other incorrect IDs
             */
            return $id > 0;
        });
    }

    /**
     * @inheritDoc
     */
    public function getChatInfo(int $chatId): array
    {
        $chat = $this->getVkChat($chatId);

        return [
            'id' => $chatId,
            'title' => $chat['chat_settings']['title']
        ];
    }

    private function getVkChat($peer_id)
    {
        return array_shift($this->apiClient->messages()->getConversationsById($this->token, [
            'peer_ids' => $peer_id
        ])['items']);
    }
}
