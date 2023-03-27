<?php

namespace EddieRusinskas\LaravelScoutOpenSearch\Services;

use Illuminate\Database\Eloquent\Collection;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;

class OpenSearchClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = ClientBuilder::fromConfig([
            "hosts"                => [
                config("scout.opensearch.host")
            ],
            "basic_authentication" => [
                "username" => config("scout.opensearch.username"),
                "password" => config("scout.opensearch.password"),
            ]
        ]);
    }

    public function createIndex(string $index): void
    {
        $this->client->indices()->create([
            "index" => $index
        ]);
    }

    public function deleteIndex(string $index): void
    {
        $this->client->indices()->delete([
            "index" => $index
        ]);
    }

    public function bulkUpdate(string $index, Collection $models): callable|array
    {
        $data = $models->map(function ($model) use ($index) {
            return [
                "update" => [
                    "_index" => $index,
                    "_id"    => $model['objectID']
                ],
                "doc"    => $model
            ];
        })->toArray();

        return $this->client->bulk([
            "index" => $index,
            "body"  => $data
        ]);
    }

    public function bulkDelete(string $index, Collection $keys): callable|array
    {
        $data = $keys->map(function ($key) use ($index) {
            return [
                "delete" => [
                    "_index" => $index,
                    "_id"    => $key
                ],
            ];
        })->toArray();

        return $this->client->bulk([
            "index" => $index,
            "body"  => $data
        ]);
    }

    public function search(string $index, string $query, array $options = [])
    {
        return $this->client->search(array_merge([
            'index'               => $index,
            'simple_query_string' => [
                'query'            => "*$query*",
                'analyze_wildcard' => true
            ]
        ], $options));
    }
}