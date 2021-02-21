<?php

namespace tests\Unit\Romaind\PizzaStore\Domain\Model\Pizza;

use Money\Money;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
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

    public function it_has_an_uuid_mutator(UuidInterface $uuid)
    {
        $this->setUuid($uuid);
        $this->getUuid()->shouldReturn($uuid);
    }

    public function it_has_a_name_mutator()
    {
        $this->setName('4 cheeses')->shouldBe($this);
        $this->getName()->shouldReturn('4 cheeses');
    }

    public function it_has_a_description_mutator()
    {
        $this->setDescription('a description')->shouldBe($this);
        $this->getDescription()->shouldReturn('a description');
    }

    public function it_has_a_create_date_mutator(\DateTime $createdAt)
    {
        $this->setCreatedAt($createdAt)->shouldBe($this);
        $this->getCreatedAt()->shouldReturn($createdAt);
    }

    public function it_has_a_update_date_mutator(\DateTime $updatedAt)
    {
        $this->setUpdatedAt($updatedAt)->shouldBe($this);
        $this->getUpdatedAt()->shouldReturn($updatedAt);
    }

    public function it_should_return_an_aggredate_route_id(UuidInterface $uuid)
    {
        $uuid->toString()->willReturn('49519410-dd26-4582-adea-52eab3b00ba9');
        $this->setUuid($uuid);
        $this->getAggregateRootId()->shouldReturn('49519410-dd26-4582-adea-52eab3b00ba9');
    }

    public function it_add_an_ingredient(Ingredient $ingredient)
    {
        $this->addIngredient($ingredient)->shouldReturn($this);
    }

    public function it_calculate_an_unit_price_with_many_ingredients(
        Ingredient $ingredient1,
        Ingredient $ingredient2
    ) {
        $ingredient1Price = Money::EUR(50);
        $ingredient2Price = Money::EUR(70);
        $ingredient1->getUnitPrice()
            ->willReturn($ingredient1Price)->shouldBeCalledTimes(1);
        $ingredient2->getUnitPrice()
            ->willReturn($ingredient2Price)->shouldBeCalledTimes(1);
        $this->addIngredient($ingredient1);
        $this->addIngredient($ingredient2);

        $totalPrice = $this->getUnitPrice();
        $totalPrice->getAmount()->shouldReturn('120');
    }

    public function it_throws_a_logic_exception_without_ingredient()
    {
        $this->shouldThrow(\LogicException::class)->during('getUnitPrice', []);
    }
}
