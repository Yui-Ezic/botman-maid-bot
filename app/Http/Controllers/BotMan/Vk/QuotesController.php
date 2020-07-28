<?php


namespace App\Http\Controllers\BotMan\Vk;


use App\Http\Controllers\Controller;
use App\Services\Bot\Vk\MessageCreator;
use App\Services\Bot\Vk\VkUsersService;
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
     * QuotesController constructor.
     *
     * @param QuoteService $quoteService
     * @param VkUsersService $usersService
     */
    public function __construct(QuoteService $quoteService, VkUsersService $usersService)
    {
        $this->quoteService = $quoteService;
        $this->usersService = $usersService;
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
                $bot->reply('Для создания цитаты используйте команду /q в ответ или вместе с пересланным сообщением.');
                return;
            }

            $image = new Image($this->quoteService->createForVk($messageForQuote->getAuthorId(), $messageForQuote->getText()));

            $user = $this->usersService->getUser($message->getAuthorId());
        } catch (Throwable $e) {
            $bot->reply('При создании цитаты произошла ошибка. Разработчики уже побежали ее исправлять 🏃‍');
            throw $e;
        }

        $message = OutgoingMessage::create('*id' . $user['id'] . ' (' . $user['first_name'] . '), цитата готова ✅.')
            ->withAttachment($image);
        $bot->reply($message);
    }
}