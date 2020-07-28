<?php


namespace Services\Bot\Vk;


use App\Entities\Bot\Messages\Message;
use App\Services\Bot\Vk\MessageCreator;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageCreatorTest extends TestCase
{
    use WithFaker;

    public function testResolveSimpleMessage(): void
    {
        $resolver = new MessageCreator();

        $messagePayload = $this->generateSimpleMessagePayload();

        $resolvedMessage = $resolver->createFromJson($messagePayload);

        $this->assertMessage($resolvedMessage, $messagePayload);
        self::assertEmpty($resolvedMessage->getForwardedMessages());
        self::assertFalse($resolvedMessage->hasForwardedMessages());
        self::assertNull($resolvedMessage->getReplyTo());
        self::assertFalse($resolvedMessage->hasReplyTo());
    }

    /**
     * Returns message payload with only from_id and text fields.
     *
     * @return array
     */
    private function generateSimpleMessagePayload(): array
    {
        return [
            'from_id' => $this->faker->randomNumber(),
            'text' => $this->faker->text(),
        ];
    }

    /**
     * Compares fromId and text fields from resolved message with source message payload
     *
     * @param Message $resolved
     * @param array $messagePayload
     */
    private function assertMessage(Message $resolved, array $messagePayload): void
    {
        self::assertSame($resolved->getAuthorId(), $messagePayload['from_id']);
        self::assertSame($resolved->getText(), $messagePayload['text']);
    }

    public function testResolveMessageWithReply(): void
    {
        $resolver = new MessageCreator();

        $replyMessage = $this->generateSimpleMessagePayload();

        $messagePayload = [
            'from_id' => $this->faker->randomNumber(),
            'text' => $this->faker->text(),
            'reply_message' => $replyMessage
        ];

        $resolvedMessage = $resolver->createFromJson($messagePayload);

        $this->assertMessage($resolvedMessage, $messagePayload);
        self::assertEmpty($resolvedMessage->getForwardedMessages());
        self::assertFalse($resolvedMessage->hasForwardedMessages());
        self::assertNotNull($resolvedMessage->getReplyTo());
        self::assertTrue($resolvedMessage->hasReplyTo());
        $this->assertMessage($resolvedMessage->getReplyTo(), $replyMessage);
    }

    public function testResolveMessageWithForwardedMessage(): void
    {
        $resolver = new MessageCreator();

        $forwardedMessage = $this->generateSimpleMessagePayload();

        $messagePayload = [
            'from_id' => $this->faker->randomNumber(),
            'text' => $this->faker->text(),
            'fwd_messages' => [
                $forwardedMessage
            ]
        ];

        $resolvedMessage = $resolver->createFromJson($messagePayload);

        $this->assertMessage($resolvedMessage, $messagePayload);
        self::assertNotEmpty($resolvedMessage->getForwardedMessages());
        self::assertSame(count($resolvedMessage->getForwardedMessages()), 1);
        $this->assertMessage($resolvedMessage->getForwardedMessages()[0], $forwardedMessage);
        self::assertNull($resolvedMessage->getReplyTo());
    }

    public function testForwardedMessageInsideReply(): void
    {
        $resolver = new MessageCreator();

        $forwardedMessage = $this->generateSimpleMessagePayload();
        $replyMessage = $this->generateSimpleMessagePayload();
        $replyMessage['fwd_messages'] = [$forwardedMessage];

        $messagePayload = [
            'from_id' => $this->faker->randomNumber(),
            'text' => $this->faker->text(),
            'reply_message' => $replyMessage
        ];

        $resolvedMessage = $resolver->createFromJson($messagePayload);
        $reply = $resolvedMessage->getReplyTo();

        $this->assertMessage($resolvedMessage, $messagePayload);
        self::assertEmpty($resolvedMessage->getForwardedMessages());
        self::assertNotNull($reply);
        $this->assertMessage($reply, $replyMessage);
        self::assertSame(count($reply->getForwardedMessages()), 1);
        $this->assertMessage($reply->getForwardedMessages()[0], $forwardedMessage);
    }
}