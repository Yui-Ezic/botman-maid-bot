<?php


namespace App\Http\Controllers\BotMan;


use App\Entities\Bot\Messages\Message;
use App\Exceptions\Quotes\CannotRetrieveMessageForQuote;
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

            $messageForQuote = $this->getMessageForQuote($message);

            if (!$messageForQuote->isAuthorInfoCanBeRetrieved()) {
                $bot->reply($this->messageService->getMessage('quotes.author_not_allowed'));
                return;
            }

            if (!$messageForQuote->getText()) {
                $bot->reply($this->messageService->getMessage('empty_message_not_allowed'));
                return;
            }

            $image = new Image($quoteService->createForVk($messageForQuote));

            $user = $usersService->getUser($message->getAuthorId());

            $message = OutgoingMessage::create($this->messageService->getMessage('quotes.done', [
                'user_id' => $user['id'],
                'user_name' => $user['first_name']
            ]))->withAttachment($image);

            $bot->reply($message);
        } catch (CannotRetrieveMessageForQuote $e) {
            $bot->reply($this->messageService->getMessage('quotes.help'));
        } catch (DomainException $e) {
            $bot->reply($e->getMessage());
        } catch (Throwable $e) {
            $bot->reply($this->messageService->getMessage('quotes.unknown_error'));
            throw $e;
        }
    }

    /**
     * Retrieves a message for a quote
     *
     * @param Message $message incoming message
     *
     * @return Message
     *
     * @throws CannotRetrieveMessageForQuote
     */
    private function getMessageForQuote(Message $message): Message
    {
        if ($message->hasReplyTo()) {
            return $message->getReplyTo();
        }

        if ($message->hasForwardedMessages()) {
            return $message->getForwardedMessages()[0];
        }

        throw new CannotRetrieveMessageForQuote('To create a quote, the message must contain a reply or forwarded message(s).');
    }
}