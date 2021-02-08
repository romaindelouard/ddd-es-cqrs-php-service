<?php

namespace Romaind\PizzaStore\UI\Http\Rest\Response;

use Symfony\Component\HttpFoundation\Response;

interface ResponseBuilderInterface
{
    public function createResponse(array $data, string $status): Response;
}
