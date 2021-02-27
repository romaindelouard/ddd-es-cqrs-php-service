<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Application\Query\Authentication\GetUserByEmail;

use Romaind\PizzaStore\Application\Query\QueryHandlerInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\GetUserCredentialsByEmailInterface;
use Romaind\PizzaStore\Infrastructure\User\Authentication\Authentication;

class GetUserByEmailHandler implements QueryHandlerInterface
{
    private GetUserCredentialsByEmailInterface $userCredentialsByEmail;

    public function __construct(
        GetUserCredentialsByEmailInterface $userCredentialsByEmail
    ) {
        $this->userCredentialsByEmail = $userCredentialsByEmail;
    }

    public function __invoke(GetUserByEmailQuery $query): Authentication
    {
        [$uuid, $email, $hashedPassword] = $this->userCredentialsByEmail->getCredentialsByEmail($query->email);

        return Authentication::create($uuid, $email, $hashedPassword);
    }
}
