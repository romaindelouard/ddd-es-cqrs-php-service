<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Domain\Event\User;

use Broadway\Serializer\Serializable;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Event\User\UserEmailChanged;
use Romaind\PizzaStore\Domain\Model\Shared\ValueObject\DateTime;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class UserEmailChangedSpec extends ObjectBehavior
{
    public function let(
        UuidInterface $uuid,
        Email $email,
        DateTime $updatedAt
    ) {
        $this->beConstructedWith($uuid, $email, $updatedAt);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserEmailChanged::class);
        $this->shouldImplement(Serializable::class);
    }

    public function it_should_serialize(
        UuidInterface $uuid,
        Email $email,
        DateTime $updatedAt
    ) {
        $uuidString = 'c036a189-32b8-4a20-8682-a6ea35a099ee';
        $emailString = 'toto@domain.com';
        $dateString = '2021-02-18 15:14:56.000000';
        $uuid->toString()
            ->willReturn($uuidString)
            ->shouldBeCalledTimes(1);
        $email->toString()
            ->willReturn($emailString)
            ->shouldBeCalledTimes(1);
        $updatedAt->toString()
            ->willReturn($dateString)
            ->shouldBeCalledTimes(1);

        $this->beConstructedWith($uuid, $email, $updatedAt);

        $this->serialize()->shouldBe([
            'uuid' => $uuidString,
            'email' => $emailString,
            'updated_at' => $dateString,
        ]);
    }

    public function it_should_deserialize()
    {
        $uuidString = 'c036a189-32b8-4a20-8682-a6ea35a099ee';
        $emailString = 'toto@domain.com';
        $dateString = '2021-02-18T15:14:56.000000';

        $event = $this::deserialize([
            'uuid' => $uuidString,
            'email' => $emailString,
            'updated_at' => $dateString,
        ]);
        $event->shouldHaveType(UserEmailChanged::class);
        $event->uuid->toString()->shouldBe($uuidString);
        $event->email->toString()->shouldBe($emailString);
    }
}
