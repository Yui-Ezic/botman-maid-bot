<?php


namespace App\UseCases\Bot;


use App\Entities\Bot\Messages\Message;
use App\Services\Bot\UsersService;
use App\Services\Images\ImagickTrimmer;
use App\Services\Quotes\QuotesMaker;
use Illuminate\Contracts\Filesystem\Filesystem;
use ImagickException;
use Throwable;

class QuoteService
{
    /**
     * @var UsersService
     */
    private $usersService;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ImagickTrimmer
     */
    private $imageTrimmer;

    /**
     * @var QuotesMaker
     */
    private $quotesMaker;

    /**
     * QuoteService constructor.
     * @param UsersService $usersService
     * @param Filesystem $filesystem
     * @param ImagickTrimmer $imageTrimmer
     * @param QuotesMaker $quotesMaker
     */
    public function __construct(UsersService $usersService,
                                Filesystem $filesystem,
                                ImagickTrimmer $imageTrimmer,
                                QuotesMaker $quotesMaker)
    {
        $this->usersService = $usersService;
        $this->filesystem = $filesystem;
        $this->imageTrimmer = $imageTrimmer;
        $this->quotesMaker = $quotesMaker;
    }

    /**
     * Creates an image with quote for vk message.
     * @param Message $message incoming message
     * @return string image url
     * @throws ImagickException
     * @throws Throwable
     */
    public function createForVk(Message $message): string
    {
        $authorId = $message->getAuthorId();

        // Get author info
        $author = $this->usersService->getUserWithPhoto100px($authorId);

        // Convert Html to Image
        $photo = $message->hasPhotos() ? $message->getPhotos()[0]->getUrl() : null;
        $content = $this->quotesMaker->make($message->getText(), $author, 'quotes.vk', $photo);

        // Remove edges and save
        $name = uniqid("vk/$authorId/quote-", true) . '.png';
        $this->filesystem->put("public/$name", $this->imageTrimmer->trimImage($content));

        return asset("storage/$name");
    }
}