<?php

namespace App\Listeners\ProfanityFound;

use App\Events\ProfanityFound;

class SendUserNotification
{
    /**
     * Handle the event.
     *
     * @param  ProfanityFound  $event
     * @return void
     */
    public function handle(ProfanityFound $event): void
    {
        $bot = $event->getBot();
        $words = implode(', ', $event->getWords());
        $bot->reply('Обнаружен мат(ы): ' . $words);
    }
}
