<?php


namespace App\Http\Middleware\Botman;


use App\Services\Bot\ChatService;
use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class OnlyAdmins implements Matching
{
    /**
     * @var ChatService
     */
    private $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    /**
     * Skips further only messages from the administrators
     *
     * @inheritDoc
     */
    public function matching(IncomingMessage $message, $pattern, $regexMatched): bool
    {
        return $regexMatched && $this->chatService->isUserAdmin($message->getRecipient(), $message->getSender());
    }
}