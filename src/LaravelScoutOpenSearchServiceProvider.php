<?php

namespace EddieRusinskas\LaravelScoutOpenSearch;

use EddieRusinskas\LaravelScoutOpenSearch\Engines\OpenSearchEngine;
use EddieRusinskas\LaravelScoutOpenSearch\Services\OpenSearchClient;
use Laravel\Scout\EngineManager;
use OpenSearch\ClientBuilder;

class LaravelScoutOpenSearchServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(): void
    {
        resolve(EngineManager::class)->extend('opensearch', function () {
            return new OpenSearchEngine(
                new OpenSearchClient(
                    (new ClientBuilder())
                        ->setHosts([config('scout.opensearch.host')])
                        ->setBasicAuthentication(config('scout.opensearch.username'), config('scout.opensearch.password'))
                        ->build()
                )
            );
        });
    }
}