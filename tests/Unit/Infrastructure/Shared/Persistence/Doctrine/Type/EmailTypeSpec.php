<?php

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type\EmailType;

class EmailTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EmailType::class);
        $this->shouldBeAnInstanceOf(StringType::class);
    }

    public function it_should_convert_to_database_value_with_nullable_value(
        AbstractPlatform $platform
    ) {
        $this->convertToDatabaseValue(null, $platform)->shouldBe(null);
    }

    public function it_should_convert_to_database_value_with_email_type_value(
        AbstractPlatform $platform,
        Email $value
    ) {
        $value->toString()
            ->willReturn('toto@domain.com')
            ->shouldBeCalledTimes(1);

        $this->convertToDatabaseValue($value, $platform)->shouldBe('toto@domain.com');
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

    public function it_should_convert_to_php_value_with_email_value(
        AbstractPlatform $platform
    ) {
        $emailString = 'toto@domain.com';
        $email = $this->convertToPHPValue($emailString, $platform);
        $email->shouldHaveType(Email::class);
        $email->toString()->shouldbe($emailString);
    }

    public function it_should_throw_a_php_conversion_exception_with_an_invalid_type_value_to_database_value(
        AbstractPlatform $platform
    ) {
        $this->shouldThrow(ConversionException::class)
            ->during('convertToPHPValue', ['badvalue', $platform]);
    }

    public function it_should_return_true_on_requires_sql_comment_hint(
        AbstractPlatform $platform
    ) {
        $this->requiresSQLCommentHint($platform)->shouldBe(true);
    }

    public function it_should_get_type_name()
    {
        $this->getName()->shouldBe('email');
    }
}
