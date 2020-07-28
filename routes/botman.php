<?php

use App\Http\Controllers\BotMan\Vk\QuotesController;
use App\Http\Controllers\BotManController;
use App\Http\Middleware\Botman\ProfanityFilter;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\VK\VkCommunityCallbackDriver;

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

$botman->group(['driver' => [VkCommunityCallbackDriver::class]], static function (BotMan $bot) {
    $bot->hears('/q', QuotesController::class . '@createQuote');
});