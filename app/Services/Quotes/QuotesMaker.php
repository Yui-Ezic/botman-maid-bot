<?php


namespace App\Services\Quotes;


use App\UseCases\Bot\Exception\ConvertHtmlToImageException;
use mikehaertl\wkhtmlto\Image;
use Throwable;

class QuotesMaker
{
    /**
     * @var Image
     */
    private $wkhtmltoimage;

    public function __construct(Image $wkhtmltoimage)
    {
        $this->wkhtmltoimage = $wkhtmltoimage;
    }

    /**
     * @param string $text quote text
     * @param array $author author info with photo (received from UserService)
     *
     * @param string $view
     * @return string image RAW content
     *
     * @throws Throwable
     */
    public function make(string $text, array $author, string $view): string
    {
        $this->wkhtmltoimage->setPage(view($view, [
            'text' => $text,
            'author' => $author['first_name'] . ($author['last_name'] ? ' ' . $author['last_name'] : ''),
            'avatar' => $author['photo']
        ])->render());

        if (($image = $this->wkhtmltoimage->toString()) === false) {
            throw new ConvertHtmlToImageException($this->wkhtmltoimage->getError());
        }

        return $image;
    }
}