<?php


namespace App\Http\Middleware\Botman;


use App\Jobs\CheckMessageForProfanity;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class ProfanityFilter implements Received
{
    /**
     * Dispatches job which check message for profanity to the queue
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        CheckMessageForProfanity::dispatch($bot->getDriver());

        return $next($message);
    }
}