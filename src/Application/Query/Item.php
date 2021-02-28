<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Application\Query;

use Broadway\ReadModel\SerializableReadModel;

class Item
{
    public string $id;
    public string $type;
    public array $resource;
    public array $relationships = [];

    private function __construct(
        string $id,
        string $type,
        array $payload,
        array $relations = []
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->resource = $payload;
        $this->relationships = $relations;
    }

    private static function type(SerializableReadModel $model): string
    {
        $path = \explode('\\', \get_class($model));

        return \array_pop($path);
    }

    public static function fromSerializable(SerializableReadModel $serializableReadModel, array $relations = []): self
    {
        return new self(
            $serializableReadModel->getId(),
            self::type($serializableReadModel),
            $serializableReadModel->serialize(),
            $relations
        );
    }

    public static function fromPayload(string $id, string $type, array $payload, array $relations = []): self
    {
        return new self(
            $id,
            $type,
            $payload,
            $relations
        );
    }
}
