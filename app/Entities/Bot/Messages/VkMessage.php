<?php


namespace App\Entities\Bot\Messages;


class VkMessage extends Message
{

    /**
     * @inheritDoc
     */
    public function isAuthorInfoCanBeRetrieved(): bool
    {
        return !($this->getAuthorId() <= 0);
    }
}