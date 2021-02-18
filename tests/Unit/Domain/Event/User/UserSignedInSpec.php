<?php

namespace tests\Unit\Romaind\PizzaStore\Domain\Event\User;

use Broadway\Serializer\Serializable;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Event\User\UserSignedIn;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class UserSignedInSpec extends ObjectBehavior
{
    public function let(UuidInterface $uuid, Email $email)
    {
        $this->beConstructedWith($uuid, $email);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserSignedIn::class);
        $this->shouldImplement(Serializable::class);
    }

    public function it_should_serialize(UuidInterface $uuid, Email $email)
    {
        $uuidString = '648fa6ea-74de-4ae5-9752-910e1494d54c';
        $emailString = 'toto@domain.com';
        $uuid->toString()
            ->willReturn($uuidString)
            ->shouldBeCalledTimes(1);
        $email->toString()
            ->willReturn($emailString)
            ->shouldBeCalledTimes(1);

        $this->beConstructedWith($uuid, $email);

        $this->serialize()->shouldBe([
            'uuid' => $uuidString,
            'email' => $emailString,
        ]);
    }

    public function it_should_deserialize()
    {
        $uuidString = 'c036a189-32b8-4a20-8682-a6ea35a099ee';
        $emailString = 'toto@domain.com';

        $event = $this::deserialize([
            'uuid' => $uuidString,
            'email' => $emailString,
        ]);
        $event->shouldHaveType(UserSignedIn::class);
        $event->uuid->toString()->shouldBe($uuidString);
        $event->email->toString()->shouldBe($emailString);
    }
}
