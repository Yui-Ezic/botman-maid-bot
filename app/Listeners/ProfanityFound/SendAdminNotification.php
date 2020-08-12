<?php

namespace App\Listeners\ProfanityFound;

use App\Events\ProfanityFound;
use App\Exceptions\Bot\UnsupportedDriverException;
use App\Factories\Bot\ChatServiceFactory;
use App\Services\Messages\MessageService;
use BotMan\BotMan\Exceptions\Base\BotManException;

class SendAdminNotification
{
    /**
     * @var ChatServiceFactory
     */
    private $chatServiceFactory;

    /**
     * @var MessageService
     */
    private $messageService;

    public function __construct(ChatServiceFactory $chatServiceFactory, MessageService $messageService)
    {
        $this->chatServiceFactory = $chatServiceFactory;
        $this->messageService = $messageService;
    }

    /**
     * Handle the event.
     *
     * @param ProfanityFound $event
     * @return void
     * @throws BotManException|UnsupportedDriverException
     */
    public function handle(ProfanityFound $event): void
    {
        $bot = $event->getBot();

        $chatService = $this->chatServiceFactory->create($bot->getDriver());

        if ($chatService->isChat($bot->getMessage())) {
            $words = implode(', ', $event->getWords());
            $chatId = $bot->getMessage()->getRecipient();

            $admins = $chatService->getAdminsList($chatId);
            $chat = $chatService->getChatInfo($chatId);
            foreach ($admins as $admin) {
                try {
                    $bot->say($this->messageService->getMessage('chat/notification.profanity_found_admin_notification', [
                        'text' => $event->getMessage()->getText(),
                        'user_id' => $event->getMessage()->getSender(),
                        'words' => $words,
                        'chat_name' => $chat['title']
                    ]), $admin);
                } catch (BotManException $exception) {
                    info("Message sending to $admin failed with exception " . $exception->getMessage());
                }
            }
        }
    }
}
