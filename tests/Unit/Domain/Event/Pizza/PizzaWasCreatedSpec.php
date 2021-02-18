<?php

namespace tests\Unit\Romaind\PizzaStore\Domain\Event\Pizza;

use Broadway\Serializer\Serializable;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Event\Pizza\PizzaWasCreated;

class PizzaWasCreatedSpec extends ObjectBehavior
{
    public function let(UuidInterface $uuid)
    {
        $uuid->toString()
            ->willReturn('519a531e-d1ab-4579-b853-dfe87e341d3c')
            ->shouldBeCalledTimes(1);

        $this->beConstructedWith($uuid);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PizzaWasCreated::class);
        $this->shouldImplement(Serializable::class);
    }

    public function it_should_serialize()
    {
        $this->serialize()->shouldBe([
            'uuid' => '519a531e-d1ab-4579-b853-dfe87e341d3c',
        ]);
    }

    public function it_should_deserialize()
    {
        $event = $this->deserialize(['uuid' => '519a531e-d1ab-4579-b853-dfe87e341d3c']);
        $event->shouldHaveType(PizzaWasCreated::class);
        $event->uuid->shouldBe('519a531e-d1ab-4579-b853-dfe87e341d3c');
    }
}
