<?php

use Laravel\Scout\EngineManager;

class LaravelScoutOpenSearchServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        resolve(EngineManager::class)->extend('opensearcj', function () {
            return new OpenSearchEngine;
        });
    }
}