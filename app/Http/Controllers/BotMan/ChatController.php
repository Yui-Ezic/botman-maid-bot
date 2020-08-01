<?php


namespace App\Http\Controllers\BotMan;


use App\Services\Bot\ChatService;
use App\Services\Bot\UsersService;
use App\Services\Messages\LaravelMessageService;
use App\Services\Messages\MessageService;
use App\UseCases\Bot\RemoveUserService;
use BotMan\BotMan\BotMan;
use DomainException;
use Throwable;

class ChatController
{
    /**
     * @var LaravelMessageService
     */
    private $messageService;

    /**
     * QuotesController constructor.
     *
     * @param MessageService $messageService
     */
    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * @param BotMan $bot
     * @param $user
     * @throws Throwable
     */
    public function removeUser(BotMan $bot, $user): void
    {
        try {
            /**
             * Resolve dependencies
             */
            $chatService = app(ChatService::class);
            $usersService = app(UsersService::class);

            (new RemoveUserService($chatService, $usersService))->remove($bot->getMessage()->getRecipient(), $user);

            $bot->reply($this->messageService->getMessage('chat/kick.success'));
        } catch (DomainException $e) {
            $bot->reply($e->getMessage());
        } catch (Throwable $e) {
            $bot->reply($this->messageService->getMessage('chat/kick.error'));
            throw $e;
        }
    }
}