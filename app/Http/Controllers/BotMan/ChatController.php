<?php


namespace App\Http\Controllers\BotMan;


use App\Exceptions\Bot\Chat\PermissionDeniedToRemoveUser;
use App\Exceptions\Bot\Chat\UserHasAlreadyBeenRemoved;
use App\Exceptions\Bot\InvalidUserIdException;
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
        } catch (PermissionDeniedToRemoveUser $e) {
            $bot->reply($this->messageService->getMessage('chat/kick.permission_denied'));
        } catch (UserHasAlreadyBeenRemoved $e) {
            $bot->reply($this->messageService->getMessage('chat/kick.user_already_removed'));
        } catch (InvalidUserIdException $e) {
            $bot->reply($this->messageService->getMessage('chat/kick.invalid_user_id'));
        } catch (Throwable $e) {
            $bot->reply($this->messageService->getMessage('chat/kick.error'));
            throw $e;
        }
    }
}