<?php

namespace Romaind\PizzaStore\Domain\Model\User\Exception;

class EmailAlreadyExistException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Email already registered.');
    }
}
