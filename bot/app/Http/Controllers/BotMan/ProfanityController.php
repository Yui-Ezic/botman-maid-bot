<?php


namespace App\Http\Controllers\BotMan;


use App\Entities\Profanity\Subscriber;
use App\Exceptions\Bot\Profanity\CannotWriteToUser;
use App\Exceptions\Bot\Profanity\UserAlreadySubscribed;
use App\Services\Bot\UsersService;
use App\Services\Messages\MessageService;
use App\UseCases\Profanity\ProfanityService;
use BotMan\BotMan\BotMan;
use Throwable;

class ProfanityController
{
    /**
     * @var UsersService
     */
    private $usersService;

    /**
     * @var ProfanityService
     */
    private $profanityService;

    /**
     * @var MessageService
     */
    private $messageService;

    public function __construct(UsersService $usersService, ProfanityService $profanityService, MessageService $messageService)
    {
        $this->usersService = $usersService;
        $this->profanityService = $profanityService;
        $this->messageService = $messageService;
    }

    /**
     * @param BotMan $bot
     * @throws Throwable
     */
    public function subscribe(BotMan $bot): void
    {
        try {
            $message = $bot->getMessage();
            $this->profanityService->subscribe($message->getSender(), $message->getRecipient());
            $bot->reply($this->messageService->getMessage('profanity/subscribe.successfully_subscribed'));
        } catch (UserAlreadySubscribed $exception) {
            $bot->reply($this->messageService->getMessage('profanity/subscribe.already_subscribed'));
        } catch (CannotWriteToUser $exception) {
            $bot->reply($this->messageService->getMessage('profanity/subscribe.verification_failed'));
        } catch (Throwable $exception) {
            $bot->reply($this->messageService->getMessage('profanity/subscribe.error'));
            throw $exception;
        }
    }

    /**
     * @param BotMan $bot
     * @throws Throwable
     */
    public function unsubscribe(BotMan $bot): void
    {
        try {
            $message = $bot->getMessage();
            $this->profanityService->unsubscribe($message->getSender(), $message->getRecipient());
            $bot->reply($this->messageService->getMessage('profanity/subscribe.successfully_unsubscribed'));
        } catch (Throwable $exception) {
            $bot->reply($this->messageService->getMessage('profanity/subscribe.error'));
            throw $exception;
        }
    }

    /**
     * @param BotMan $bot
     * @throws Throwable
     */
    public function list(BotMan $bot): void
    {
        try {
            $message = $bot->getMessage();
            $subscribers = $this->profanityService->getSubscribersList($message->getRecipient());

            if ($subscribers->isEmpty()) {
                $bot->reply($this->messageService->getMessage('profanity/subscribe.empty_list'));
                return;
            }

            $text = [];
            foreach ($subscribers as $subscriber) {
                /** @var Subscriber $subscriber */
                $user = $this->usersService->getUser($subscriber->platform_id);
                $text[] = $user['first_name'] . ' ' . $user['last_name'];
            }
            $bot->reply($this->messageService->getMessage('profanity/subscribe.list', [
                'list' => implode(PHP_EOL, $text)
            ]));
        } catch (Throwable $exception) {
            $bot->reply($this->messageService->getMessage('profanity/subscribe.error'));
            throw $exception;
        }
    }
}