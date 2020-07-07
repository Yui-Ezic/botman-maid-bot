<?php


namespace App\UseCases\Bot;


use App\Services\Bot\UsersService;
use App\UseCases\Bot\Exception\ConvertHtmlToImageException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Imagick;
use ImagickException;
use mikehaertl\wkhtmlto\Image;
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
     * QuoteService constructor.
     * @param UsersService $usersService
     * @param Filesystem $filesystem
     */
    public function __construct(UsersService $usersService, Filesystem $filesystem)
    {
        $this->usersService = $usersService;
        $this->filesystem = $filesystem;
    }

    /**
     * Creates an image with quote for vk message.
     * @param int $fromId unique identifier of vk user
     * @param string $text quote text
     * @return string image url
     * @throws Throwable
     * @throws ImagickException
     * @throws ConvertHtmlToImageException
     */
    public function createForVk(int $fromId, string $text): string
    {
        // Get author info
        $author = $this->usersService->getUserWithPhoto100px($fromId);

        // Convert Html to Image
        $image = app(Image::class);
        $image->setPage(view('quotes.vk', [
            'text' => $text,
            'author' => $author['first_name'] . ($author['last_name'] ? ' ' . $author['last_name'] : ''),
            'avatar' => $author['photo']
        ])->render());
        if (($content = $image->toString()) === false) {
            throw new ConvertHtmlToImageException($image->getError());
        }

        // Remove edges and save
        $name = uniqid("vk/$fromId/quote-", true);
        $this->filesystem->put("public/$name", $this->trimImage($content));

        return asset("storage/$name");
    }

    /**
     * Remove edges from the image
     * @param string $image
     * @return Imagick
     * @throws ImagickException
     */
    private function trimImage(string $image): Imagick
    {
        $imagick = new Imagick();

        if (!$imagick->readImageBlob($image)) {
            throw new ImagickException('Cannot read quote image blob.');
        }

        if (!$imagick->trimImage(0)) {
            throw new ImagickException('Cannot trim quote image.');
        }

        return $imagick;
    }
}