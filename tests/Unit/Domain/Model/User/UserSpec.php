<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\User;

use Broadway\Domain\DomainMessage;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\Exception\InvalidCredentialsException;
use Romaind\PizzaStore\Domain\Model\User\Specification\UniqueEmailSpecificationInterface;
use Romaind\PizzaStore\Domain\Model\User\User;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\Credentials;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class UserSpec extends ObjectBehavior
{
    public function let(
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
        UuidInterface $uuid,
        Credentials $credentials,
        Email $email,
        HashedPassword $hashedPassword
    ) {
        $uuidString = 'b9818e51-bf83-4cdc-be1e-3f54cc8bb938';
        $emailString = 'romain.delouard@domain.com';
        $uuid->toString()->willReturn($uuidString);
        $email->toString()->willReturn($emailString);
        $credentials->email = $email;
        $credentials->password = $hashedPassword;
        $uniqueEmailSpecification->isUnique(Argument::any())->willReturn(true);
        $this->beConstructedThrough('create', [
            $uuid,
            $credentials,
            $uniqueEmailSpecification,
        ]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(User::class);
        $this->shouldImplement(EventSourcedAggregateRoot::class);
    }

    public function it_should_create_an_user_with_valid_email(
        UniqueEmailSpecificationInterface $uniqueEmailSpecification,
        UuidInterface $uuid,
        Credentials $credentials,
        Email $email,
        HashedPassword $hashedPassword
    ) {
        $emailString = 'romain.delouard@domain.com';
        $uuidString = 'b9818e51-bf83-4cdc-be1e-3f54cc8bb938';

        $uuid->toString()->willReturn($uuidString);
        $email->__toString()->willReturn($emailString);
        $email->toString()->willReturn($emailString);
        $uniqueEmailSpecification->isUnique($emailString)->willReturn(true);
        $credentials->email = $email;
        $credentials->password = $hashedPassword;

        $this->beConstructedThrough('create', [
            $uuid,
            $credentials,
            $uniqueEmailSpecification,
        ]);

        $this->updatedAt()->shouldBeNull();
        $this->CreatedAt()->shouldNotBeNull();
        $this->email()->shouldBe($emailString);
        $this->uuid()->shouldBe($uuidString);
        $this->getAggregateRootId()->shouldBe($uuidString);

        $events = $this->getUncommittedEvents();
        $events->getIterator()->count()->shouldBe(1);
        $event = $events->getIterator()->current();
        $event->shouldHaveType(DomainMessage::class);
        $event->getType()->shouldBe('Romaind.PizzaStore.Domain.Event.User.UserWasCreated');
    }

    public function it_should_sign_in_with_valid_credentials(
        HashedPassword $hashedPassword
    ) {
        $plainPassword = 'mypassword';
        $hashedPassword->match($plainPassword)
            ->willReturn(true)->shouldBeCalledTimes(1);
        $this->signIn($plainPassword);

        $events = $this->getUncommittedEvents();
        $events->getIterator()->count()->shouldBe(2);

        $iterator = $events->getIterator();
        $event = $iterator->current();
        $event->shouldHaveType(DomainMessage::class);
        $event->getType()->shouldBe('Romaind.PizzaStore.Domain.Event.User.UserWasCreated');

        $iterator->next();
        $event = $iterator->current();
        $event->shouldHaveType(DomainMessage::class);
        $event->getType()->shouldBe('Romaind.PizzaStore.Domain.Event.User.UserSignedIn');
    }

    public function it_should_throw_invalid_credential_exception_when_sign_in_with_invalid_credentials(
        HashedPassword $hashedPassword
    ) {
        $plainPassword = 'mypassword';
        $hashedPassword->match($plainPassword)
            ->willReturn(false)->shouldBeCalledTimes(1);

        $events = $this->getUncommittedEvents();
        $events->getIterator()->count()->shouldBe(1);
        $event = $events->getIterator()->current();
        $event->shouldHaveType(DomainMessage::class);
        $event->getType()->shouldBe('Romaind.PizzaStore.Domain.Event.User.UserWasCreated');

        $this->shouldThrow(InvalidCredentialsException::class)
            ->during('signIn', [$plainPassword]);
    }

    public function it_should_change_email(
        Email $newEmail,
        UniqueEmailSpecificationInterface $uniqueEmailSpecification
    ) {
        $emailString = 'toto@newdomain.com';
        $newEmail->toString()->willReturn($emailString);
        $uniqueEmailSpecification->isUnique($newEmail)->shouldBeCalledTimes(1);

        $this->changeEmail($newEmail, $uniqueEmailSpecification);
    }
}
