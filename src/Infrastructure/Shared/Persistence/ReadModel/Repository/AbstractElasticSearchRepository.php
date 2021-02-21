<?php

namespace Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Repository;

use Assert\Assertion;
use Assert\AssertionFailedException;
use Elasticsearch\Client;

abstract class AbstractElasticSearchRepository
{
    private Client $client;

    public function __construct(Client $eventReadModelClient)
    {
        $this->client = $eventReadModelClient;
    }

    abstract protected function index(): string;

    public function search(array $query): array
    {
        $finalQuery = [];

        $finalQuery['index'] = $this->index();
        $finalQuery['body'] = $query;

        return $this->client->search($finalQuery);
    }

    public function refresh(): void
    {
        if ($this->client->indices()->exists(['index' => $this->index()])) {
            $this->client->indices()->refresh(['index' => $this->index()]);
        }
    }

    public function delete(): void
    {
        if ($this->client->indices()->exists(['index' => $this->index()])) {
            $this->client->indices()->delete(['index' => $this->index()]);
        }
    }

    public function reboot(): void
    {
        $this->delete();
        $this->boot();
    }

    public function boot(): void
    {
        if (!$this->client->indices()->exists(['index' => $this->index()])) {
            $this->client->indices()->create(['index' => $this->index()]);
        }
    }

    protected function add(array $document): array
    {
        return $this->client->index([
            'index' => $this->index(),
            'id' => $document['id'] ?? null,
            'body' => $document,
        ]);
    }

    /**
     * @throws AssertionFailedException
     */
    public function page(int $page = 1, int $limit = 50): array
    {
        Assertion::greaterThan($page, 0, 'Pagination need to be > 0');

        $query = [];

        $query['index'] = $this->index();
        $query['from'] = ($page - 1) * $limit;
        $query['size'] = $limit;

        $response = $this->client->search($query);

        return [
            'data' => \array_column($response['hits']['hits'], '_source'),
            'total' => $response['hits']['total'],
        ];
    }

    public function isHealthly(): bool
    {
        try {
            $response = $this->client->cluster()->health();

            return 'red' !== $response['status'];
        } catch (\Throwable $err) {
            return false;
        }
    }
}
