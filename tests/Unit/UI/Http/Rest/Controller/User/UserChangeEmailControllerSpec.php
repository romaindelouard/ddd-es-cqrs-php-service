<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Rest\Controller\User;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\UuidInterface;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\User\ChangeEmail\ChangeEmailCommand;
use Romaind\PizzaStore\Domain\Model\User\Exception\ForbiddenException;
use Romaind\PizzaStore\Infrastructure\User\Authentication\Authentication;
use Romaind\PizzaStore\UI\Http\Rest\Controller\AbstractCommandController;
use Romaind\PizzaStore\UI\Http\Rest\Controller\User\UserChangeEmailController;
use Romaind\PizzaStore\UI\Http\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserChangeEmailControllerSpec extends ObjectBehavior
{
    public function let(
        Session $session,
        CommandBusInterface $commandBus
    ) {
        $this->beConstructedWith($session, $commandBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(UserChangeEmailController::class);
        $this->shouldBeAnInstanceOf(AbstractCommandController::class);
    }

    public function it_should_change_user_email(
        Session $session,
        CommandBusInterface $commandBus,
        Request $request,
        Authentication $authentication,
        UuidInterface $uuid
    ) {
        $uuidString = '91135d53-d995-4b21-a5a9-323a226fe857';
        $email = 'toto@domain.com';

        $request->get('email')->willReturn($email)->shouldBeCalledTimes(1);
        $session->get()->willReturn($authentication)->shouldBeCalledTimes(1);
        $authentication->uuid()->willReturn($uuid)->shouldBeCalledTimes(1);
        $uuid->equals(Argument::type(UuidInterface::class))
            ->willReturn(true)->shouldBeCalledTimes(1);

        $commandBus
            ->handle(Argument::allOf(
                Argument::type(ChangeEmailCommand::class),
                Argument::that(function ($command) use ($uuidString, $email) {
                    if ($command->userUuid->toString() !== $uuidString) {
                        return false;
                    }
                    if ($command->email->toString() !== $email) {
                        return false;
                    }

                    return true;
                })
            ))
            ->shouldBeCalledTimes(1);

        $response = $this->__invoke($uuidString, $request);
        $response->getStatusCode()->shouldBe(Response::HTTP_NO_CONTENT);
    }

    public function it_should_not_change_email_if_session_uuid_is_different(
        Session $session,
        CommandBusInterface $commandBus,
        Request $request,
        Authentication $authentication,
        UuidInterface $uuid
    ) {
        $uuidString = '91135d53-d995-4b21-a5a9-323a226fe857';
        $email = 'toto@domain.com';

        $session->get()->willReturn($authentication)->shouldBeCalledTimes(1);
        $authentication->uuid()->willReturn($uuid)->shouldBeCalledTimes(1);
        $uuid->equals(Argument::type(UuidInterface::class))
            ->willReturn(false)->shouldBeCalledTimes(1);

        $commandBus->handle(Argument::any())->shouldNotBeCalled();
        $this
            ->shouldThrow(ForbiddenException::class)
            ->during('__invoke', [$uuidString, $request]);
    }
}
