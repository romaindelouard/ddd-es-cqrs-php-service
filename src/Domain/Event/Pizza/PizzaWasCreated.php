<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Event\Pizza;

use Broadway\Serializer\Serializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PizzaWasCreated implements Serializable
{
    public string $uuid;

    public function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid->toString();
    }

    public function serialize(): array
    {
        return ['uuid' => $this->uuid];
    }

    public static function deserialize(array $data): self
    {
        return new self(Uuid::fromString($data['uuid']));
    }
}
