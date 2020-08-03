<?php


namespace App\Http\Middleware\Botman;


use App\Services\Bot\ChatService;
use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class OnlyChat implements Matching
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
     * Skips further only messages from the chat
     *
     * @inheritDoc
     */
    public function matching(IncomingMessage $message, $pattern, $regexMatched)
    {
        return $this->chatService->isChat($message);
    }
}