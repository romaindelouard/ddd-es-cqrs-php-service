<?php

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\User;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;
use Romaind\PizzaStore\Domain\Model\User\Specification\UniqueEmailSpecificationInterface;
use Romaind\PizzaStore\Domain\Model\User\User;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\Credentials;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class UserSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
        $this->shouldImplement(EventSourcedAggregateRoot::class);
    }

    public function it_should_create_an_user_with_valid_email(
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $emailString = 'romain.delouard@domain.com';

        $uniqueEmailSpecification->isUnique($emailString)->willReturn(true);

        $this->beConstructedThrough('create', [
            Uuid::uuid4(),
            new Credentials(
                Email::fromString($emailString),
                HashedPassword::encode('password')
            ),
            $uniqueEmailSpecification,
        ]);

        $this->email()->shouldBe($emailString);
        $this->uuid()->shouldNotBeNull();

        $events = $this->getUncommittedEvents();

        $event = $events->getIterator()->current();

        // self::assertCount(1, $events->getIterator(), 'Only one event should be in the buffer');

        // /** @var DomainMessage $event */
        // $event = $events->getIterator()->current();

        // self::assertInstanceOf(UserWasCreated::class, $event->getPayload(), 'First event should be UserWasCreated');
    }
}
