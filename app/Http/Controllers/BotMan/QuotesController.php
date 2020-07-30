<?php


namespace App\Http\Controllers\BotMan;


use App\Http\Controllers\Controller;
use App\Services\Bot\MessageCreator;
use App\Services\Bot\UsersService;
use App\Services\Messages\LaravelMessageService;
use App\Services\Messages\MessageService;
use App\UseCases\Bot\QuoteService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use DomainException;
use Symfony\Component\HttpFoundation\ParameterBag;
use Throwable;

class QuotesController extends Controller
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
     * Creates image with quote
     *
     * @param BotMan $bot
     *
     * @throws Throwable
     */
    public function createQuote(BotMan $bot): void
    {
        try {
            /**
             * Resolve dependencies
             */
            $usersService = app(UsersService::class);
            $quoteService = app(QuoteService::class);
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

            $image = new Image($quoteService->createForVk($messageForQuote->getAuthorId(), $messageForQuote->getText()));

            $user = $usersService->getUser($message->getAuthorId());
        } catch (DomainException $e) {
            $bot->reply($e->getMessage());
            return;
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