<?php

declare(strict_types=1);

namespace Romaind\PizzaStore\Domain\Model\User\Exception;

class EmailAlreadyExistException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Email already registered.');
    }
}
