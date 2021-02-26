<?php

namespace Romaind\PizzaStore\Infrastructure\Shared\Event\ReadModel;

use Broadway\Domain\DomainMessage;
use Romaind\PizzaStore\Domain\Event\SearchEventRepositoryInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Repository\AbstractElasticSearchRepository;

class ElasticSearchEventRepository extends AbstractElasticSearchRepository implements SearchEventRepositoryInterface
{
    private const INDEX = 'events';

    protected function index(): string
    {
        return self::INDEX;
    }

    public function store(DomainMessage $message): void
    {
        $document = [
            'type' => $message->getType(),
            'payload' => $message->getPayload()->serialize(),
            'occurred_on' => $message->getRecordedOn()->toString(),
        ];

        $this->add($document);
    }
}
