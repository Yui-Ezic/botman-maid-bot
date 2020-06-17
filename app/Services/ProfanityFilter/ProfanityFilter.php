<?php


namespace App\Services\ProfanityFilter;


interface ProfanityFilter
{
    /**
     * Checking the text for the content of profanity.
     *
     * @param string $text
     * @return string[] List of bad words found
     */
    public function badWords(string $text): array;
}