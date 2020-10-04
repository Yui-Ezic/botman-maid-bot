<?php


namespace App\Services\Bot;


use App\Entities\Bot\Messages\Message;

interface MessageCreator
{
    /**
     * Creates Message object from message payload.
     *
     * @param array $messagePayload
     *
     * @return Message
     */
    public function create(array $messagePayload): Message;
}