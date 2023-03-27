<?php

namespace EddieRusinskas\LaravelScoutOpenSearch;

use Aws\Credentials\Credentials;
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
                    "endpoint"    => config("scout.opensearch.endpoint"),
                    "region"      => config("scout.opensearch.region"),
                    "version"     => config("scout.opensearch.version"),
                    "credentials" => new Credentials(config("scout.opensearch.access_key"), config("scout.opensearch.secret_key")),
                ])
            );
        });
    }
}