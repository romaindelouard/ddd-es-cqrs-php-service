<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\Domain\Model\Shared\ValueObject\DateTime;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\Type\DateTimeType;

class DateTimeTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DateTimeType::class);
        $this->shouldBeAnInstanceOf(DateTimeImmutableType::class);
    }

    public function it_should_get_sql_declaration(
        AbstractPlatform $platform
    ) {
        $column = ['date'];
        $platform->getDateTimeTypeDeclarationSQL($column)
            ->willReturn('string')
            ->shouldBeCalledTimes(1);

        $this->getSQLDeclaration($column, $platform)->shouldBe('string');
    }

    public function it_should_convert_to_database_value_with_nullable_value(
        AbstractPlatform $platform
    ) {
        $this->convertToDatabaseValue(null, $platform)->shouldBe(null);
    }

    public function it_should_convert_to_database_value_with_datetime_value(
        AbstractPlatform $platform,
        DateTime $value
    ) {
        $platform->getDateTimeFormatString()
            ->willReturn('dateTimeFormat')
            ->shouldBeCalledTimes(1);

        $value->format('dateTimeFormat')
            ->willReturn('string')
            ->shouldBeCalledTimes(1);

        $this->convertToDatabaseValue($value, $platform)->shouldBe('string');
    }

    public function it_should_convert_to_database_value_with_datetimeimmutable_value(
        AbstractPlatform $platform,
        \DateTimeImmutable $value
    ) {
        $platform->getDateTimeFormatString()
            ->willReturn('dateTimeFormat')
            ->shouldBeCalledTimes(1);

        $value->format('dateTimeFormat')
            ->willReturn('string')
            ->shouldBeCalledTimes(1);

        $this->convertToDatabaseValue($value, $platform)->shouldBe('string');
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

    public function it_should_convert_to_php_value_with_datetime_value(
        AbstractPlatform $platform,
        DateTime $value
    ) {
        $this->convertToPHPValue($value, $platform)->shouldBe($value);
    }

    public function it_should_convert_to_php_value_with_valid_string_value(
        AbstractPlatform $platform
    ) {
        $value = '2000-01-11 12:45:00';
        $convertedValue = $this->convertToPHPValue($value, $platform);
        $convertedValue->shouldHaveType(DateTime::class);
    }

    public function it_should_throw_a_php_conversion_exception_with_an_invalid_type_value_to_database_value(
        AbstractPlatform $platform
    ) {
        $this->shouldThrow(ConversionException::class)
            ->during('convertToPHPValue', ['badvalue', $platform]);
    }
}
