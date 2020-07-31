<?php


namespace App\Entities\Bot\Messages;


abstract class Message
{
    /**
     * @var int
     */
    private $authorId;

    /**
     * @var string
     */
    private $text;

    /**
     * @var Message|null
     */
    private $replyTo;

    /**
     * @var Message[]
     */
    private $forwardedMessages;

    public function __construct(int $authorId, string $text, Message $replyTo = null, array $forwardedMessages = [])
    {
        $this->authorId = $authorId;
        $this->text = $text;
        $this->replyTo = $replyTo;
        $this->forwardedMessages = $forwardedMessages;
    }

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return Message[]
     */
    public function getForwardedMessages(): array
    {
        return $this->forwardedMessages;
    }

    /**
     * @return Message|null
     */
    public function getReplyTo(): ?Message
    {
        return $this->replyTo;
    }

    /**
     * @param Message $message
     */
    public function addForwardedMessage(Message $message): void
    {
        $this->forwardedMessages[] = $message;
    }

    public function hasForwardedMessages(): bool
    {
        return !empty($this->forwardedMessages);
    }

    public function hasReplyTo(): bool
    {
        return $this->replyTo !== null;
    }

    /**
     * Returns false for authors whose information cannot be retrieved
     *
     * @return bool
     */
    abstract public function isAuthorInfoCanBeRetrieved(): bool;
}