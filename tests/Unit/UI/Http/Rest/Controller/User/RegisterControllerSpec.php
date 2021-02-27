<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Rest\Controller\User;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\User\SignUp\SignUpCommand;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractCommandController;
use Romaind\PizzaStore\UI\Http\Rest\Controller\User\RegisterController;
use Romaind\PizzaStore\UI\Http\Rest\Response\OpenApi;
use Symfony\Component\HttpFoundation\Request;

class RegisterControllerSpec extends ObjectBehavior
{
    public function let(CommandBusInterface $commandBus)
    {
        $this->beConstructedWith($commandBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RegisterController::class);
        $this->shouldBeAnInstanceOf(AbstractCommandController::class);
    }

    public function it_should_register_a_user(
        CommandBusInterface $commandBus,
        Request $request
    ) {
        $uuid = '88b1aafe-91f9-4064-a317-97cbe5ec5ce3';
        $request->get('uuid')->willReturn($uuid)->shouldBeCalledTimes(1);
        $email = 'toto@domain.com';
        $request->get('email')->willReturn($email)->shouldBeCalledTimes(1);
        $plainPassword = 'mypassword';
        $request->get('password')->willReturn($plainPassword)->shouldBeCalledTimes(1);

        $commandBus
            ->handle(Argument::allOf(
                Argument::type(SignUpCommand::class),
                Argument::that(function ($command) use ($uuid, $email) {
                    if ($command->uuid->toString() !== $uuid) {
                        return false;
                    }
                    if ($command->credentials->email->toString() !== $email) {
                        return false;
                    }

                    return true;
                })
            ))
            ->shouldBeCalledTimes(1);
        $response = $this->__invoke($request);
        $response->getStatusCode()->shouldBe(OpenApi::HTTP_CREATED);
    }
}
