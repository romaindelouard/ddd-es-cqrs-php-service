<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\Pizza;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Domain\Event\Pizza\PizzaWasCreated;
use Romaind\PizzaStore\Domain\Model\Ingredient\Ingredient;
use Romaind\PizzaStore\Domain\Model\Product\ProductInterface;

/**
 * @ORM\Entity
 * Use a design pattern composite (ComboPizza & Pizza)
 */
class Pizza extends EventSourcedAggregateRoot implements ProductInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private UuidInterface $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $updatedAt = null;

    private array $ingredients = [];

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public static function create(UuidInterface $uuid, string $name, ?string $description): Pizza
    {
        $pizza = new self();
        $pizza->setUuid($uuid);
        $pizza->setName($name);
        $pizza->setDescription($description);
        $pizza->apply(new PizzaWasCreated($uuid));

        return $pizza;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getAggregateRootId(): string
    {
        return $this->getUuid()->toString();
    }

    public function getUnitPrice(): Money
    {
        $max = count($this->ingredients);
        if (0 === $max) {
            throw new \LogicException('No price without ingredient');
        }
        $totalPrice = $this->ingredients[0]->getUnitPrice();
        for ($i = 1; $i < $max; ++$i) {
            $totalPrice = $totalPrice->add($this->ingredients[$i]->getUnitPrice());
        }

        return $totalPrice;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        $this->ingredients[] = $ingredient;

        return $this;
    }
}
