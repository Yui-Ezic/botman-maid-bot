<?php


namespace App\Services\ProfanityFilter;


use Snipe\BanBuilder\CensorWords;

class BanBuilder implements ProfanityFilter
{
    /**
     * @inheritDoc
     */
    public function badWords(string $text): array
    {
        $censor = new CensorWords;
        $censor->setDictionary(config('banbuilder.dictionaries.rus.blacklist'));
        $censor->addWhiteList(config('banbuilder.dictionaries.rus.whitelist'));
        return $censor->censorString(mb_strtolower($text))['matched'];
    }
}