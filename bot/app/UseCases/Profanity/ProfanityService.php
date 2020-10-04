<?php

namespace App\UseCases\Profanity;

use App\Entities\Profanity\Subscriber;
use App\Exceptions\Bot\Profanity\CannotWriteToUser;
use App\Exceptions\Bot\Profanity\UserAlreadySubscribed;
use Illuminate\Database\Eloquent\Collection;

interface ProfanityService
{
    /**
     * @param int $userId
     * @param int $chatId
     * @throws UserAlreadySubscribed
     * @throws CannotWriteToUser
     */
    public function subscribe(int $userId, int $chatId): void;

    /**
     * @param int $userId
     * @param int $chatId
     */
    public function unsubscribe(int $userId, int $chatId): void;

    /**
     * @param int $chatId
     * @return Collection|Subscriber[]
     */
    public function getSubscribersList(int $chatId): Collection;
}