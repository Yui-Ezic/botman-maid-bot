<?php


namespace App\Factories\Bot;


use App\Exceptions\Bot\UnsupportedDriverException;
use App\Services\Bot\ChatService;
use App\Services\Bot\Vk\VkChatService;
use BotMan\BotMan\Interfaces\DriverInterface;
use Illuminate\Contracts\Container\Container;

class ChatServiceFactory
{
    /**
     * @var Container
     */
    private $container;

    /**
     * ChatServiceFactory constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param DriverInterface $driver
     *
     * @return ChatService
     *
     * @throws UnsupportedDriverException
     */
    public function create(DriverInterface $driver): ChatService
    {
        switch ($driver->getName())
        {
            case 'VkCommunityCallback':
                return $this->container->make(VkChatService::class);
            default:
                throw new UnsupportedDriverException('Unsupported driver.', $driver);
        }
    }
}