<?php

use App\Exceptions\Bot\UnsupportedDriverException;
use App\Http\Controllers\BotMan\ChatController;
use App\Http\Controllers\BotMan\ProfanityController;
use App\Http\Controllers\BotMan\QuotesController;
use App\Http\Controllers\BotManController;
use App\Http\Middleware\Botman\OnlyAdmins;
use App\Http\Middleware\Botman\OnlyChat;
use App\Http\Middleware\Botman\ProfanityFilter;
use App\Services\Bot\Vk\VkChatService;
use BotMan\BotMan\BotMan;
use BotMan\Drivers\VK\VkCommunityCallbackDriver;

/**@var $botman BotMan */
$botman = resolve('botman');

$botman->middleware->received(app(ProfanityFilter::class));

$botman->hears('Hi', function (BotMan $bot) {
    $bot->reply('Hello!');
});
$botman->hears('Start conversation', BotManController::class . '@startConversation');

$botman->group(['driver' => [VkCommunityCallbackDriver::class]], static function (BotMan $botman) {
    $botman->on("confirmation", static function () {
        echo config('botman.vk.confirmation_string');
    });

    $botman->hears('/q', QuotesController::class . '@createQuote');

    $vkChatService = app(VkChatService::class);

    $botman->group(['middleware' => [
        new OnlyChat($vkChatService),
        new OnlyAdmins($vkChatService)
    ]], static function (BotMan $botman) {
        $botman->hears('/kick {user}', ChatController::class . '@removeUser');

        $botman->hears('/profanity subscribe', ProfanityController::class . '@subscribe');
        $botman->hears('/profanity unsubscribe', ProfanityController::class . '@unsubscribe');
        $botman->hears('/profanity list', ProfanityController::class . '@list');
    });
});

$botman->exception(UnsupportedDriverException::class, static function ($exception, BotMan $bot) {
    if ($bot->getMessage()->getRecipient()) {
        $bot->say('Эта комманда не поддерживается вашей платформой.', $bot->getMessage()->getRecipient());
    }

    throw $exception;
});
