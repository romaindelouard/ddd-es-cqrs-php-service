<?php

namespace Romaind\PizzaStore\Infrastructure\User\ReadModel\Projector;

use Assert\AssertionFailedException;
use Broadway\ReadModel\Projector;
use Doctrine\ORM\NonUniqueResultException;
use Romaind\PizzaStore\Domain\Event\User\UserEmailChanged;
use Romaind\PizzaStore\Domain\Event\User\UserWasCreated;
use Romaind\PizzaStore\Domain\Model\Shared\Exception\DateTimeException;
use Romaind\PizzaStore\Infrastructure\Shared\Persistence\ReadModel\Exception\NotFoundException;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\Postgres\PostgresReadModelUserRepository;
use Romaind\PizzaStore\Infrastructure\User\ReadModel\UserView;

class UserProjectionFactory extends Projector
{
    private PostgresReadModelUserRepository $repository;

    public function __construct(PostgresReadModelUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws AssertionFailedException
     * @throws DateTimeException
     */
    protected function applyUserWasCreated(UserWasCreated $userWasCreated): void
    {
        $userReadModel = UserView::fromSerializable($userWasCreated);

        $this->repository->add($userReadModel);
    }

    /**
     * @throws NotFoundException
     * @throws NonUniqueResultException
     */
    protected function applyUserEmailChanged(UserEmailChanged $emailChanged): void
    {
        $userReadModel = $this->repository->oneByUuid($emailChanged->uuid);

        $userReadModel->changeEmail($emailChanged->email);
        $userReadModel->changeUpdatedAt($emailChanged->updatedAt);

        $this->repository->apply();
    }
}
