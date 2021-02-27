<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Rest\Response;

use PhpSpec\ObjectBehavior;
use Romaind\PizzaStore\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\HttpFoundation\JsonResponse;

class OpenApiSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(OpenApi::class);
        $this->shouldBeAnInstanceOf(JsonResponse::class);
    }
}
