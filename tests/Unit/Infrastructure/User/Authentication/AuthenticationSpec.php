<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\Authentication;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\User\Authentication\Authentication;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSpec extends ObjectBehavior
{
    public function let(
        UuidInterface $uuid,
        Email $email,
        HashedPassword $hashedPassword
    ) {
        $this->beConstructedThrough('create', [
            $uuid,
            $email,
            $hashedPassword,
        ]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Authentication::class);
        $this->shouldImplement(UserInterface::class);
        $this->shouldImplement(EncoderAwareInterface::class);
    }

    public function it_should_get_an_username(Email $email)
    {
        $email->toString()->willReturn('toto@domain.com');

        $this->getUsername()->shouldBe('toto@domain.com');
    }

    public function it_should_get_a_password(HashedPassword $hashedPassword)
    {
        $hashedPassword->toString()->willReturn('hashed password');

        $this->getPassword()->shouldBe('hashed password');
    }

    public function it_should_return_nullable_salt()
    {
        $this->getSalt()->shouldBe(null);
    }

    public function it_should_return_encoder_name()
    {
        $this->getEncoderName()->shouldBe('bcrypt');
    }

    public function it_should_return_uuid(UuidInterface $uuid)
    {
        $this->uuid()->shouldBe($uuid);
    }

    public function it_should_nothing_on_erase_credentials()
    {
        $this->eraseCredentials();
    }

    public function it_should_get_roles()
    {
        $this->getRoles()->shouldBe(['ROLE_USER']);
    }

    public function it_should_convert_to_string(Email $email)
    {
        $email->toString()->willReturn('toto@domain.com');

        $this->__toString()->shouldBe('toto@domain.com');
    }
}
