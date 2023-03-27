<?php

namespace EddieRusinskas\LaravelScoutOpenSearch;

use EddieRusinskas\LaravelScoutOpenSearch\Engines\OpenSearchEngine;
use Laravel\Scout\EngineManager;

class LaravelScoutOpenSearchServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        resolve(EngineManager::class)->extend('opensearch', function () {
            return new OpenSearchEngine;
        });
    }
}