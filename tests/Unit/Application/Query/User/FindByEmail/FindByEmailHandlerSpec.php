<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Application\Query\User\FindByEmail;

use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Query\Item;
use Romaind\PizzaStore\Application\Query\QueryHandlerInterface;
use Romaind\PizzaStore\Application\Query\User\FindByEmail\FindByEmailHandler;
use Romaind\PizzaStore\Application\Query\User\FindByEmail\FindByEmailQuery;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres\PostgresReadModelUserRepository;

class FindByEmailHandlerSpec extends ObjectBehavior
{
    public function let(PostgresReadModelUserRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(FindByEmailHandler::class);
        $this->shouldImplement(QueryHandlerInterface::class);
    }

    public function it_should_handle(
        FindByEmailQuery $query,
        PostgresReadModelUserRepository $repository,
        Email $email,
        UuidInterface $uuid
    ) {
        $query->email = $email;
        $uuid->toString()
            ->willReturn('83683f79-9747-4341-8beb-afdc8ae55814')
            ->shouldBeCalledTimes(1);

        $repository->oneByEmailAsArray($email)
            ->willReturn(['uuid' => $uuid])->shouldBeCalledTimes(1);

        $item = $this->__invoke($query);
        $item->shouldHaveType(Item::class);
    }
}
