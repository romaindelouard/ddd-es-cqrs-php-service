<?php

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\Pizza;

use Money\Money;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;
use Romaind\PizzaStore\Domain\Model\Ingredient\Ingredient;
use Romaind\PizzaStore\Domain\Model\Pizza\Pizza;
use Romaind\PizzaStore\Domain\Model\Product\ProductInterface;

class PizzaSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith(
            Uuid::fromString('3d211801-4d00-4f64-9123-bf61c0065349'),
            '4 cheeses',
            'it is a wonderfully pizza'
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(Pizza::class);
        $this->shouldBeAnInstanceOf(ProductInterface::class);
    }

    public function it_should_create_a_pizza(): void
    {
        $this->beConstructedWith(
            Uuid::fromString('3d211801-4d00-4f64-9123-bf61c0065349'),
            '4 cheeses',
            'it is wonderfully pizza'
        );

        $tomato = Ingredient::create(
            Uuid::fromString('33fdf62a-6057-4432-9d0a-c4ac179a1573'),
            'tomato',
            Money::EUR(50)
        );
        $this->addIngredient($tomato);
        $feta = Ingredient::create(
            Uuid::fromString('d864702d-4471-47c7-a7e9-6a0926e09fbb'),
            'Feta cheese',
            Money::EUR(150)
        );
        $this->addIngredient($feta);
        $this->getUnitPrice()->getAmount()->shouldReturn('200');
    }
}
