<?php

namespace App\Events;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ProfanityFound
{
    use Dispatchable, SerializesModels;

    /**
     * @var array
     */
    private $words;

    /**
     * @var BotMan
     */
    private $bot;

    /**
     * @var IncomingMessage
     */
    private $message;

    /**
     * Create a new event instance.
     *
     * @param array $words
     * @param BotMan $bot
     * @param IncomingMessage $message
     */
    public function __construct(array $words, BotMan $bot, IncomingMessage $message)
    {
        $this->words = $words;
        $this->bot = $bot;
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getWords(): array
    {
        return $this->words;
    }

    /**
     * @return BotMan
     */
    public function getBot(): BotMan
    {
        return $this->bot;
    }

    /**
     * @return IncomingMessage
     */
    public function getMessage(): IncomingMessage
    {
        return $this->message;
    }
}
