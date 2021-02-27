<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\Specification;

use Doctrine\ORM\NonUniqueResultException;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\Exception\EmailAlreadyExistException;
use Romaind\PizzaStore\Domain\Model\User\Repository\CheckUserByEmailInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\User\Specification\UniqueEmailSpecification;

class UniqueEmailSpecificationSpec extends ObjectBehavior
{
    public function let(CheckUserByEmailInterface $checkUserByEmail)
    {
        $this->beConstructedWith($checkUserByEmail);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UniqueEmailSpecification::class);
    }

    public function it_should_return_true_with_unique_email(
        CheckUserByEmailInterface $checkUserByEmail,
        Email $email
    ) {
        $checkUserByEmail
            ->existsEmail($email)
            ->willReturn(null)
            ->shouldBeCalledTimes(1);

        $this->isUnique($email)->shouldBe(true);
    }

    public function it_should_throw_email_already_exists_if_user_identifier_exists(
        CheckUserByEmailInterface $checkUserByEmail,
        Email $email,
        UuidInterface $uuid
    ) {
        $checkUserByEmail
            ->existsEmail($email)
            ->willReturn($uuid)
            ->shouldBeCalledTimes(1);

        $this->shouldThrow(EmailAlreadyExistException::class)
            ->during('isUnique', [$email]);
    }

    public function it_should_throw_email_already_exists_if_user_exists(
        CheckUserByEmailInterface $checkUserByEmail,
        Email $email
    ) {
        $checkUserByEmail
            ->existsEmail($email)
            ->willThrow(NonUniqueResultException::class)
            ->shouldBeCalledTimes(1);

        $this->shouldThrow(EmailAlreadyExistException::class)
            ->during('isUnique', [$email]);
    }
}
