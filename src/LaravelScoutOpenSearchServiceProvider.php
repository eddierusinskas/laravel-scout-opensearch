<?php

namespace EddieRusinskas\LaravelScoutOpenSearch;

use EddieRusinskas\LaravelScoutOpenSearch\Engines\OpenSearchEngine;
use EddieRusinskas\LaravelScoutOpenSearch\Services\OpenSearchClient;
use Laravel\Scout\EngineManager;

class LaravelScoutOpenSearchServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        resolve(EngineManager::class)->extend('opensearch', function () {
            return new OpenSearchEngine(
                OpenSearchClient::createFromConfig([
                    "hosts"                => [
                        config("scout.opensearch.host")
                    ],
                    "basic_authentication" => [
                        "username" => config("scout.opensearch.username"),
                        "password" => config("scout.opensearch.password"),
                    ]
                ])
            );
        });
    }
}