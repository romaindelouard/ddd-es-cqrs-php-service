<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Rest\Controller\Authentication;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\User\SignIn\SignInCommand;
use Romaind\PizzaStore\Application\Query\Authentication\GetToken\GetTokenQuery;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractCommandQueryController;
use Romaind\PizzaStore\UI\Http\Rest\Controller\Authentication\CheckController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CheckControllerSpec extends ObjectBehavior
{
    public function let(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        UrlGeneratorInterface $router
    ) {
        $this->beConstructedWith(
            $commandBus,
            $queryBus,
            $router
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(CheckController::class);
        $this->shouldBeAnInstanceOf(AbstractCommandQueryController::class);
    }

    public function it_should_check_an_authentication_user(
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus,
        Request $request
    ) {
        $email = 'toto@domain.com';
        $password = 'mypassword';

        $request->get('_username')
            ->willReturn($email)->shouldBeCalledTimes(1);
        $request->get('_password')
            ->willReturn($password)->shouldBeCalledTimes(1);

        $commandBus
            ->handle(Argument::allOf(
                Argument::type(SignInCommand::class),
                Argument::that(function ($command) use ($email, $password) {
                    if ($command->email->toString() !== $email) {
                        return false;
                    }
                    if ($command->plainPassword !== $password) {
                        return false;
                    }

                    return true;
                })
            ))
            ->shouldBeCalledTimes(1);
        $queryBus->ask(Argument::type(GetTokenQuery::class))
            ->willReturn('mytoken')->shouldBeCalledTimes(1);
        $result = $this->__invoke($request);
        $result->getContent()->shouldBe(json_encode(['token' => 'mytoken']));
    }
}
