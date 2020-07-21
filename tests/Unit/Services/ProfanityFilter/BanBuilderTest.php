<?php


namespace Services\ProfanityFilter;


use App\Services\ProfanityFilter\BanBuilder;
use Tests\TestCase;

class BanBuilderTest extends TestCase
{
    /**
     * @var BanBuilder|null
     */
    private $profanityFilter = null;

    /**
     * Returns singleton of BanBuilder
     * @return BanBuilder
     */
    public function getProfanityFilter(): BanBuilder
    {
        if($this->profanityFilter === null) {
            $this->profanityFilter = app(BanBuilder::class);
        }

        return $this->profanityFilter;
    }

    /**
     * Data provider with offensive words
     * @return array|string[][]
     */
    public function badWordsProvider(): array
    {
           return [
               ['бля'],
               ['ебло']
           ];
    }

    /**
     * @param $text
     * @dataProvider badWordsProvider
     */
    public function testProfanityFound(string $text): void
    {
        $found = $this->getProfanityFilter()->badWords($text);
        self::assertNotEmpty($found);
        self::assertSame(mb_strtolower($text), $found[0]);
    }

    /**
     * Data provider without offensive words
     * @return array|string[]
     */
    public function goodWordsProvider(): array
    {
        return [
            ['мандарины'],
            ['расслабляться'],
            ['командой'],
            ['неблагополучным']
        ];
    }

    /**
     * @param $text
     * @dataProvider goodWordsProvider
     */
    public function testProfanityNotFound(string $text): void
    {
        $found = $this->getProfanityFilter()->badWords($text);
        self::assertEmpty($found);
    }
}