<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Rest\Controller\User;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Query\Item;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\Application\Query\User\FindByEmail\FindByEmailQuery;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractQueryController;
use Romaind\PizzaStore\UI\Http\Rest\Controller\User\GetUserByEmailController;
use Romaind\PizzaStore\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GetUserByEmailControllerSpec extends ObjectBehavior
{
    public function let(
        QueryBusInterface $queryBus,
        UrlGeneratorInterface $router
    ) {
        $this->beConstructedWith($queryBus, $router);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(GetUserByEmailController::class);
        $this->shouldBeAnInstanceOf(AbstractQueryController::class);
    }

    public function it_should_retrieve_a_user_with_a_email(
        QueryBusInterface $queryBus,
        Item $item
    ) {
        $item->id = 'identifier';
        $item->type = 'userClass';
        $item->resource = [];
        $email = 'toto@domain.com';
        $queryBus
            ->ask(Argument::allOf(
                Argument::type(FindByEmailQuery::class),
                Argument::that(function ($query) use ($email) {
                    if ($query->email->toString() !== $email) {
                        return false;
                    }

                    return true;
                })
            ))
            ->willReturn($item)
            ->shouldBeCalledTimes(1);
        $result = $this->__invoke($email);
        $result->getStatusCode()->shouldBe(OpenApi::HTTP_OK);
    }
}
