<?php

namespace tests\Unit\Romaind\PizzaStore\Domain\Event\User;

use Broadway\Serializer\Serializable;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Event\User\UserWasCreated;
use Romaind\PizzaStore\Domain\Model\Shared\ValueObject\DateTime;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\Credentials;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class UserWasCreatedSpec extends ObjectBehavior
{
    public function let(
        UuidInterface $uuid,
        Credentials $credentials,
        DateTime $createdAt
    ) {
        $this->beConstructedWith($uuid, $credentials, $createdAt);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserWasCreated::class);
        $this->shouldImplement(Serializable::class);
    }

    public function it_should_serialize(
        UuidInterface $uuid,
        Credentials $credentials,
        DateTime $createdAt,
        Email $email,
        HashedPassword $hashedPassword
    ) {
        $uuidString = '648fa6ea-74de-4ae5-9752-910e1494d54c';
        $emailString = 'toto@domain.com';
        $password = 'mypassword';
        $dateString = '2021-02-18T15:14:56.000000';
        $uuid->toString()
            ->willReturn($uuidString)
            ->shouldBeCalledTimes(1);
        $email->toString()
            ->willReturn($emailString)
            ->shouldBeCalledTimes(1);
        $hashedPassword->toString()
            ->willReturn($password)
            ->shouldBeCalledTimes(1);
        $createdAt->toString()
            ->willReturn($dateString)
            ->shouldBeCalledTimes(1);

        $credentials->email = $email;
        $credentials->password = $hashedPassword;

        $this->beConstructedWith($uuid, $credentials, $createdAt);

        $this->serialize()->shouldBe([
            'uuid' => $uuidString,
            'credentials' => [
                'email' => $emailString,
                'password' => $password,
            ],
            'created_at' => $dateString,
        ]);
    }

    public function it_should_deserialize()
    {
        $uuidString = '648fa6ea-74de-4ae5-9752-910e1494d54c';
        $emailString = 'toto@domain.com';
        $password = 'mypassword';
        $dateString = '2021-02-18T15:14:56.000000';

        $event = $this::deserialize([
            'uuid' => $uuidString,
            'credentials' => [
                'email' => $emailString,
                'password' => $password,
            ],
            'created_at' => $dateString,
        ]);
        $event->shouldHaveType(UserWasCreated::class);
        $event->credentials->email->toString()->shouldBe($emailString);
        $event->credentials->password->toString()->shouldBe($password);
        $event->uuid->toString()->shouldBe($uuidString);
    }
}
