<?php

namespace Romaind\PizzaStore\Application\Query\User\FindByEmail;

use Doctrine\ORM\NonUniqueResultException;
use Romaind\PizzaStore\Application\Query\Item;
use Romaind\PizzaStore\Application\Query\QueryHandlerInterface;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres\PostgresReadModelUserRepository;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\UserView;

class FindByEmailHandler implements QueryHandlerInterface
{
    private PostgresReadModelUserRepository $repository;

    public function __construct(PostgresReadModelUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    public function __invoke(FindByEmailQuery $query): Item
    {
        $userView = $this->repository->oneByEmailAsArray($query->email);

        return Item::fromPayload($userView['uuid']->toString(), UserView::TYPE, $userView);
    }
}
