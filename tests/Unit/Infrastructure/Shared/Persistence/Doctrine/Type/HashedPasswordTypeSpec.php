<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Authentication\HashedPassword;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type\HashedPasswordType;

class HashedPasswordTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(HashedPasswordType::class);
        $this->shouldBeAnInstanceOf(StringType::class);
    }

    public function it_should_convert_to_database_value_with_nullable_value(
        AbstractPlatform $platform
    ) {
        $this->convertToDatabaseValue(null, $platform)->shouldBe(null);
    }

    public function it_should_convert_to_database_value_with_hashed_password_type_value(
        AbstractPlatform $platform,
        HashedPassword $value
    ) {
        $value->toString()
            ->willReturn('hashedpassword')
            ->shouldBeCalledTimes(1);

        $this->convertToDatabaseValue($value, $platform)->shouldBe('hashedpassword');
    }

    public function it_should_throw_a_database_conversion_exception_with_an_invalid_type_value_to_database_value(
        AbstractPlatform $platform,
        \stdClass $value
    ) {
        $this->shouldThrow(ConversionException::class)
            ->during('convertToDatabaseValue', [$value, $platform]);
    }

    public function it_should_convert_to_php_value_with_nullable_value(
        AbstractPlatform $platform
    ) {
        $this->convertToPHPValue(null, $platform)->shouldBe(null);
    }

    public function it_should_return_true_on_requires_sql_comment_hint(
        AbstractPlatform $platform
    ) {
        $this->requiresSQLCommentHint($platform)->shouldBe(true);
    }

    public function it_should_convert_to_php_value_with_hashed_password_value(
        AbstractPlatform $platform
    ) {
        $hash = 'hashedPassword';
        $email = $this->convertToPHPValue($hash, $platform);
        $email->shouldHaveType(HashedPassword::class);
    }

    public function it_should_get_type_name()
    {
        $this->getName()->shouldBe('hashed_password');
    }
}
