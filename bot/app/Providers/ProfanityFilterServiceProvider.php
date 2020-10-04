<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use App\Entities\Profanity\Whitelist;
use App\Services\ProfanityFilter\BanBuilder;
use App\Services\ProfanityFilter\ProfanityFilter;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Snipe\BanBuilder\CensorWords;

class ProfanityFilterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(CensorWords::class, function() {
            $censor = new CensorWords;
            $censor->setDictionary(config('banbuilder.dictionaries.rus.blacklist'));
            $censor->addWhiteList(config('banbuilder.dictionaries.rus.whitelist'));
            if ($this->isWhitelistTableExist()) {
                $censor->addWhiteList(Whitelist::getWhitelist());
            }
            return $censor;
        });

        $this->app->singleton(ProfanityFilter::class, static function(Application $app) {
            return new BanBuilder($app->make(CensorWords::class));
        });
    }

    /**
     * Checks the profanity_whitelist table exist in database schema
     *
     * @return bool
     */
    private function isWhitelistTableExist(): bool
    {
        return Schema::hasTable(Whitelist::TABLE_NAME);
    }
}
