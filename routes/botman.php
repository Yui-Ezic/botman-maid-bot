<?php
use App\Http\Controllers\BotManController;
use App\Http\Middleware\Botman\ProfanityFilter;

$botman = resolve('botman');

$botman->middleware->received(app(ProfanityFilter::class));

$botman->hears('Hi', function ($bot) {
    $bot->reply('Hello!');
});
$botman->hears('Start conversation', BotManController::class.'@startConversation');
