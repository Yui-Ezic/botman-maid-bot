<?php

use App\Http\Controllers\BotMan\QuotesController;
use App\Http\Controllers\BotManController;
use App\Http\Middleware\Botman\ProfanityFilter;
use BotMan\BotMan\BotMan;

/**@var $botman BotMan */
$botman = resolve('botman');

$botman->middleware->received(app(ProfanityFilter::class));

$botman->hears('Hi', function (BotMan $bot) {
    $bot->reply('Hello!');
});
$botman->hears('Start conversation', BotManController::class . '@startConversation');

$botman->on("confirmation", static function () {
    echo config('botman.vk.confirmation_string');
});

$botman->hears('/q', QuotesController::class . '@createQuote');
