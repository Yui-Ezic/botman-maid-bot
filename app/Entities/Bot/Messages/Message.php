<?php


namespace App\Entities\Bot\Messages;


use App\Entities\Bot\Photo;

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

    /**
     * @var Photo[]
     */
    private $photos;

    public function __construct(int $authorId,
                                string $text,
                                Message $replyTo = null,
                                array $forwardedMessages = [],
                                array $photos = [])
    {
        $this->authorId = $authorId;
        $this->text = $text;
        $this->replyTo = $replyTo;
        $this->forwardedMessages = $forwardedMessages;
        $this->photos = $photos;
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
     * @return Photo[]
     */
    public function getPhotos(): array
    {
        return $this->photos;
    }

    /**
     * @param Message $message
     */
    public function addForwardedMessage(Message $message): void
    {
        $this->forwardedMessages[] = $message;
    }

    /**
     * @return bool
     */
    public function hasForwardedMessages(): bool
    {
        return !empty($this->forwardedMessages);
    }

    /**
     * @return bool
     */
    public function hasReplyTo(): bool
    {
        return $this->replyTo !== null;
    }

    /**
     * @return bool
     */
    public function hasPhotos(): bool
    {
        return !empty($this->photos);
    }

    /**
     * Returns false for authors whose information cannot be retrieved
     *
     * @return bool
     */
    abstract public function isAuthorInfoCanBeRetrieved(): bool;
}