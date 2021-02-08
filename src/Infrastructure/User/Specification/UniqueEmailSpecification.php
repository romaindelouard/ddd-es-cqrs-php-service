<?php

namespace Romaind\PizzaStore\Infrastructure\User\Specification;

use Doctrine\ORM\NonUniqueResultException;
use Romaind\PizzaStore\Domain\Model\Shared\Specification\AbstractSpecification;
use Romaind\PizzaStore\Domain\Model\User\Exception\EmailAlreadyExistException;
use Romaind\PizzaStore\Domain\Model\User\Repository\CheckUserByEmailInterface;
use Romaind\PizzaStore\Domain\Model\User\Specification\UniqueEmailSpecificationInterface;
use Romaind\PizzaStore\Domain\Model\User\ValueObject\Email;

class UniqueEmailSpecification extends AbstractSpecification implements UniqueEmailSpecificationInterface
{
    private CheckUserByEmailInterface $checkUserByEmail;

    public function __construct(CheckUserByEmailInterface $checkUserByEmail)
    {
        $this->checkUserByEmail = $checkUserByEmail;
    }

    /**
     * @throws EmailAlreadyExistException
     */
    public function isUnique(Email $email): bool
    {
        return $this->isSatisfiedBy($email);
    }

    /**
     * @param Email $value
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    public function isSatisfiedBy($value): bool
    {
        try {
            if ($this->checkUserByEmail->existsEmail($value)) {
                throw new EmailAlreadyExistException();
            }
        } catch (NonUniqueResultException $e) {
            throw new EmailAlreadyExistException();
        }

        return true;
    }
}
