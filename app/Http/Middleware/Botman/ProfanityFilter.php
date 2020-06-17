<?php


namespace App\Http\Middleware\Botman;


use App\Events\ProfanityFound;
use App\Services\ProfanityFilter\ProfanityFilter as ProfanityFilterService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class ProfanityFilter implements Received
{
    /**
     * @var ProfanityFilterService
     */
    private $filter;

    public function __construct(ProfanityFilterService $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        $badWords = $this->filter->badWords($message->getText());
        if (!empty($badWords)) {
            event(new ProfanityFound($badWords, $bot, $message));
        }

        return $next($message);
    }
}