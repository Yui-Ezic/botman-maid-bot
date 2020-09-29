<?php

namespace Tests\Unit\Entities\Profanity;

use App\Entities\Profanity\Subscriber as ProfanitySubscriber;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class Subscriber extends TestCase
{
    use DatabaseTransactions;

    /**
     * @throws Exception
     */
    public function testSubscribeVkUser(): void
    {
        $subscriber = ProfanitySubscriber::subscribeVkUser(
            $userId = random_int(100, 1000),
            $chatId = random_int(100, 1000)
        );

        self::assertNotEmpty($subscriber);

        self::assertTrue($subscriber->isVkPlatform());
        self::assertSame($subscriber->platform_id, $userId);
        self::assertSame($subscriber->chat_id, $chatId);
    }

    /**
     * @throws Exception
     */
    public function testSubscribeVkUserToSeveralChatsInSamePlatform(): void
    {
        $subscriber1 = ProfanitySubscriber::subscribeVkUser(
            $userId = random_int(100, 1000),
            $chatId1 = random_int(100, 1000)
        );

        self::assertNotEmpty($subscriber1);

        self::assertTrue($subscriber1->isVkPlatform());
        self::assertSame($subscriber1->platform_id, $userId);
        self::assertSame($subscriber1->chat_id, $chatId1);

        $subscriber2 = ProfanitySubscriber::subscribeVkUser(
            $userId,
            $chatId2 = random_int(100, 1000)
        );

        self::assertNotEmpty($subscriber2);

        self::assertTrue($subscriber2->isVkPlatform());
        self::assertSame($subscriber2->platform_id, $userId);
        self::assertSame($subscriber2->chat_id, $chatId2);
    }

    /**
     * @throws Exception
     */
    public function testPreventDuplicateVkUserSubscription(): void
    {
        ProfanitySubscriber::subscribeVkUser(
            $userId = random_int(100, 1000),
            $chatId = random_int(100, 1000)
        );

        $this->expectException(QueryException::class);

        ProfanitySubscriber::subscribeVkUser(
            $userId,
            $chatId
        );
    }

    /**
     * @throws Exception
     */
    public function testUnsubscribeVkUser(): void
    {
        ProfanitySubscriber::subscribeVkUser(
            $userId = random_int(100, 1000),
            $chatId = random_int(100, 1000)
        );

        $subscriber = ProfanitySubscriber::getSubscriber($userId, $chatId, ProfanitySubscriber::PLATFORM_VK);

        self::assertNotEmpty($subscriber);

        self::assertTrue($subscriber->isVkPlatform());
        self::assertSame($subscriber->platform_id, $userId);
        self::assertSame($subscriber->chat_id, $chatId);

        ProfanitySubscriber::unsubscribeVkUser($userId, $chatId);

        self::assertEmpty(ProfanitySubscriber::getSubscriber($userId, $chatId, ProfanitySubscriber::PLATFORM_VK));
    }
}
