<?php


namespace App\Services\Messages;


class LaravelMessageService implements MessageService
{
    /**
     * @var string|null
     */
    private $locale;

    /**
     * LaravelMessageService constructor.
     *
     * @param string|null $locale
     */
    public function __construct(?string $locale = null)
    {
        $this->locale = $locale;
    }

    /**
     * @inheritDoc
     */
    public function getMessage($key, $args = [])
    {
        return __($key, $args, $this->locale);
    }
}