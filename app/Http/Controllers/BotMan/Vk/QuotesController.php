<?php


namespace App\Http\Controllers\BotMan\Vk;


use App\Http\Controllers\Controller;
use App\Services\Bot\Vk\MessageCreator;
use App\Services\Bot\Vk\VkUsersService;
use App\Services\Messages\LaravelMessageService;
use App\Services\Messages\MessageService;
use App\UseCases\Bot\QuoteService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use Symfony\Component\HttpFoundation\ParameterBag;
use Throwable;

class QuotesController extends Controller
{
    /**
     * @var QuoteService
     */
    private $quoteService;

    /**
     * @var VkUsersService
     */
    private $usersService;

    /**
     * @var LaravelMessageService
     */
    private $messageService;

    /**
     * QuotesController constructor.
     *
     * @param QuoteService $quoteService
     * @param VkUsersService $usersService
     * @param MessageService $messageService
     */
    public function __construct(QuoteService $quoteService, VkUsersService $usersService, MessageService $messageService)
    {
        $this->quoteService = $quoteService;
        $this->usersService = $usersService;
        $this->messageService = $messageService;
    }

    /**
     * Creates image with quote. Only for vk!
     *
     * @param BotMan $bot
     *
     * @throws Throwable
     */
    public function createQuote(BotMan $bot): void
    {
        try {
            $messagesCreator = app(MessageCreator::class);
            /** @var ParameterBag $payload */
            $payload = $bot->getMessage()->getPayload();
            $message = $messagesCreator->createFromJson($payload->get('object')['message']);

            if ($message->hasReplyTo()) {
                $messageForQuote = $message->getReplyTo();
            } elseif ($message->hasForwardedMessages()) {
                $messageForQuote = $message->getForwardedMessages()[0];
            } else {
                $bot->reply($this->messageService->getMessage('quotes.help'));
                return;
            }

            if ($messageForQuote->getAuthorId() <= 0) {
                $bot->reply($this->messageService->getMessage('quotes.groups_not_allowed'));
                return;
            }

            if (!$messageForQuote->getText()) {
                $bot->reply($this->messageService->getMessage('empty_message_not_allowed'));
                return;
            }

            $image = new Image($this->quoteService->createForVk($messageForQuote->getAuthorId(), $messageForQuote->getText()));

            $user = $this->usersService->getUser($message->getAuthorId());
        } catch (Throwable $e) {
            $bot->reply($this->messageService->getMessage('quotes.unknown_error'));
            throw $e;
        }

        $message = OutgoingMessage::create($this->messageService->getMessage('quotes.done', [
            'user_id' => $user['id'],
            'user_name' => $user['first_name']
        ]))->withAttachment($image);
        $bot->reply($message);
    }
}