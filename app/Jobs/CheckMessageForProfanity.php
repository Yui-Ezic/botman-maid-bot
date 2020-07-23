<?php

namespace App\Jobs;

use App\BotMan\BotManFactory;
use App\Events\ProfanityFound;
use App\Services\ProfanityFilter\ProfanityFilter;
use BotMan\BotMan\Interfaces\DriverInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CheckMessageForProfanity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * @var Request
     */
    private $driver;

    /**
     * Create a new job instance.
     *
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Execute the job.
     *
     * @param ProfanityFilter $profanityFilter
     * @return void
     */
    public function handle(ProfanityFilter $profanityFilter): void
    {
        $message = $this->driver->getMessages()[0];
        $badWords = $profanityFilter->badWords($message->getText());
        if (!empty($badWords)) {
            $botman = BotManFactory::createWithMessage($this->driver, $message);
            info('Profanities found: ' . implode(', ', $badWords));
            event(new ProfanityFound($badWords, $botman, $message));
        }
    }
}
