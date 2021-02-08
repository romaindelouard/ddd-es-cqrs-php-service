<?php

namespace Romaind\PizzaStore\Application\Query\Authentication\GetToken;

use Romaind\PizzaStore\Application\Query\QueryHandlerInterface;
use Romaind\PizzaStore\Domain\Model\User\Repository\GetUserCredentialsByEmailInterface;
use Romaind\PizzaStore\Infrastructure\User\Authentication\AuthenticationProvider;

class GetTokenHandler implements QueryHandlerInterface
{
    private GetUserCredentialsByEmailInterface $userCredentialsByEmail;
    private AuthenticationProvider $authenticationProvider;

    public function __construct(
        GetUserCredentialsByEmailInterface $userCredentialsByEmail,
        AuthenticationProvider $authenticationProvider
    ) {
        $this->authenticationProvider = $authenticationProvider;
        $this->userCredentialsByEmail = $userCredentialsByEmail;
    }

    public function __invoke(GetTokenQuery $query): string
    {
        [$uuid, $email, $hashedPassword] = $this->userCredentialsByEmail->getCredentialsByEmail($query->email);

        return $this->authenticationProvider->generateToken($uuid, $email, $hashedPassword);
    }
}
