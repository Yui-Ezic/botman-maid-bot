<?php


namespace App\Services\ProfanityFilter;


use Snipe\BanBuilder\CensorWords;

class BanBuilder implements ProfanityFilter
{
    /**
     * @var CensorWords
     */
    protected $censor;

    /**
     * BanBuilder constructor.
     * @param CensorWords $censor
     */
    public function __construct(CensorWords $censor)
    {
        $this->censor = $censor;
    }

    /**
     * @inheritDoc
     */
    public function badWords(string $text): array
    {
        return $this->censor->censorString(mb_strtolower($text))['matched'];
    }
}