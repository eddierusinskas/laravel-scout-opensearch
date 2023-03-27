<?php

namespace EddieRusinskas\LaravelScoutOpenSearch\Services;

use Aws\OpenSearchService\OpenSearchServiceClient;
use Illuminate\Database\Eloquent\Collection;
use OpenSearch\Client;

class OpenSearchClient
{
    private Client $client;

    public static function createFromConfig(array $config): OpenSearchClient
    {
        return (new static())->createClientFromConfig($config);
    }

    public function createClientFromConfig(array $config): static
    {
        $this->client = OpenSearchServiceClient::fromConfig($config);
        return $this;
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