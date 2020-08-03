<?php

namespace App\Http\Controllers;

use App\Exceptions\Bot\UnsupportedDriverException;
use BotMan\BotMan\BotMan;
use App\Conversations\ExampleConversation;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        try {
            $botman = app('botman');
            $botman->listen();
        } catch (UnsupportedDriverException $e) {
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tinker()
    {
        return view('tinker');
    }

    /**
     * Loaded through routes/botman.php
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
