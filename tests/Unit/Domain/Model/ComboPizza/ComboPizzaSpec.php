<?php

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\ComboPizza;

use Money\Money;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;
use Romaind\PizzaStore\Domain\Model\ComboPizza\ComboPizza;
use Romaind\PizzaStore\Domain\Model\Ingredient\Ingredient;
use Romaind\PizzaStore\Domain\Model\Pizza\Pizza;
use Romaind\PizzaStore\Domain\Model\Product\ProductInterface;

class ComboPizzaSpec extends ObjectBehavior
{
    public function let(): void
    {
        $pizza = Pizza::create(
            Uuid::fromString('3d211801-4d00-4f64-9123-bf61c0065349'),
            '4 cheeses',
            'it is a wonderfully pizza'
        );

        $tomato = Ingredient::create(
            Uuid::fromString('33fdf62a-6057-4432-9d0a-c4ac179a1573'),
            'tomato',
            Money::EUR(50)
        );
        $pizza->addIngredient($tomato);
        $feta = Ingredient::create(
            Uuid::fromString('d864702d-4471-47c7-a7e9-6a0926e09fbb'),
            'Feta cheese',
            Money::EUR(150)
        );
        $pizza->addIngredient($feta);

        $pizza2 = Pizza::create(
            Uuid::fromString('3d211801-4d00-4f64-9123-bf61c0065349'),
            'simple pizza',
            'it is a simple pizza'
        );
        $simpleCheese = Ingredient::create(
            Uuid::fromString('d864702d-4471-47c7-a7e9-6a0926e09fbb'),
            'simple cheese',
            Money::EUR(100)
        );
        $pizza2->addIngredient($simpleCheese);
        $pizza2->addIngredient($tomato);

        $this->beConstructedWith(
            Uuid::fromString('3d211801-4d00-4f64-9123-bf61c0065349'),
            'buy 1 pizza get 1 free',
            [$pizza, $pizza2]
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ComboPizza::class);
        $this->shouldBeAnInstanceOf(ProductInterface::class);
    }

    public function it_should_create_a_combo_with_two_pizzas(): void
    {
        $this->getUnitPrice()->getAmount()->shouldReturn('350');
    }
}
