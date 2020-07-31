<?php


namespace App\Services\Bot\Vk;


use App\Entities\Bot\Messages\Message;
use App\Entities\Bot\Messages\VkMessage;
use App\Services\Bot\MessageCreator;
use InvalidArgumentException;

class VkMessageCreator implements MessageCreator
{
    /**
     * Creates Message object from json.
     *
     * @param array $vkMessage Vk json message object (https://vk.com/dev/objects/message)
     *
     * @throws InvalidArgumentException
     *
     * @return Message
     */
    public function createFromJson(array $vkMessage): Message
    {
        $fromId = $this->getFromArrayOrFail($vkMessage, 'from_id');
        $text = $this->getFromArrayOrFail($vkMessage, 'text');
        $replyMessage = $this->getFromArray($vkMessage, 'reply_message', null);
        if ($replyMessage !== null) {
            $replyMessage = $this->createFromJson($replyMessage);
        }
        $forwardedMessages = array_map(function ($item) {
            return $this->createFromJson($item);
        }, $this->getFromArray($vkMessage, 'fwd_messages', []));
        return new VkMessage($fromId, $text, $replyMessage, $forwardedMessages);
    }

    /**
     * Returns a parameter by name if it exist or throw Exception else.
     *
     * @param array $source
     * @param $key
     *
     * @return mixed
     * @throws InvalidArgumentException
     *
     */
    private function getFromArrayOrFail(array &$source, $key)
    {
        if (!array_key_exists($key, $source)) {
            throw new InvalidArgumentException("Key '$key' doesn't exist in source array.");
        }

        return $source[$key];
    }

    /**
     * Returns a parameter by name.
     *
     * @param array $source source array
     * @param string $key The key
     * @param mixed $default The default value if the parameter key does not exist
     *
     * @return mixed
     */
    private function getFromArray(array &$source, $key, $default)
    {
        return array_key_exists($key, $source) ? $source[$key] : $default;
    }

    /**
     * @inheritDoc
     */
    public function create(array $messagePayload): Message
    {
        return $this->createFromJson($messagePayload);
    }
}