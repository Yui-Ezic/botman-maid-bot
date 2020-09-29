<?php


namespace App\UseCases\Profanity;


use App\Entities\Profanity\Subscriber;
use App\Exceptions\Bot\Profanity\CannotWriteToUser;
use App\Exceptions\Bot\Profanity\UserAlreadySubscribed;
use Illuminate\Database\Eloquent\Collection;
use VK\Client\VKApiClient;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;

class VkProfanityService implements ProfanityService
{
    /**
     * @var VKApiClient
     */
    private $apiClient;

    /**
     * @var string
     */
    private $token;

    public function __construct(VKApiClient $apiClient, string $token)
    {
        $this->apiClient = $apiClient;
        $this->token = $token;
    }

    /**
     * @param int $userId
     * @param int $chatId
     * @throws CannotWriteToUser
     * @throws VKClientException
     */
    public function subscribe(int $userId, int $chatId): void
    {
        $subscriber = Subscriber::getSubscriber($userId, $chatId, Subscriber::PLATFORM_VK);
        if ($subscriber !== null) {
            throw new UserAlreadySubscribed("User $userId already subscribed to chat $chatId.");
        }

        $this->sendVerificationMessage($userId);

        Subscriber::subscribeVkUser($userId, $chatId);
    }

    public function unsubscribe(int $userId, int $chatId): void
    {
        Subscriber::unsubscribeVkUser($userId, $chatId);
    }

    public function getSubscribersList(int $chatId): Collection
    {
        return Subscriber::vk()->get();
    }

    /**
     * @param int $userId
     * @throws CannotWriteToUser
     * @throws VKClientException
     */
    private function sendVerificationMessage(int $userId): void
    {
        try {
            $this->apiClient->messages()->send($this->token, [
                'user_id' => $userId,
                'random_id' => 0,
                'message' => 'Verification message, just ignore it',
                'peer_id' => $userId
            ]);
        } catch (VKApiException $exception) {
            throw new CannotWriteToUser($exception->getMessage());
        }
    }
}