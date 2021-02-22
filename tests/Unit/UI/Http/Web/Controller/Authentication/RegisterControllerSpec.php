<?php

namespace tests\Unit\Romaind\PizzaStore\UI\Http\Web\Controller\Authentication;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Command\CommandBusInterface;
use Romaind\PizzaStore\Application\Command\User\SignUp\SignUpCommand;
use Romaind\PizzaStore\Application\Query\QueryBusInterface;
use Romaind\PizzaStore\Domain\Model\User\Exception\EmailAlreadyExistException;
use Romaind\PizzaStore\UI\Http\Web\Controller\AbstractRenderController;
use Romaind\PizzaStore\UI\Http\Web\Controller\Authentication\RegisterController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig;

class RegisterControllerSpec extends ObjectBehavior
{
    public function let(
        Twig\Environment $template,
        CommandBusInterface $commandBus,
        QueryBusInterface $queryBus
    ) {
        $this->beConstructedWith($template, $commandBus, $queryBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RegisterController::class);
        $this->shouldBeAnInstanceOf(AbstractRenderController::class);
    }

    public function it_should_get_register_form(
        Twig\Environment $template
    ) {
        $template->render(Argument::any(), [])
            ->willReturn('')->shouldBeCalledTimes(1);

        $response = $this->get();
        $response->getStatusCode()->shouldBe(Response::HTTP_OK);
    }

    public function it_should_post_register_form_with_valid_data(
        Twig\Environment $template,
        CommandBusInterface $commandBus,
        Request $request,
        ParameterBag $parameterBag
    ) {
        $request->request = $parameterBag;
        $email = 'toto@domain.com';
        $password = 'mypassword';

        $parameterBag->get('email')
            ->willReturn($email)->shouldBeCalledTimes(1);
        $parameterBag->get('password')
            ->willReturn($password)->shouldBeCalledTimes(1);

        $template->render(Argument::cetera())->shouldBeCalledTimes(1);

        $commandBus
            ->handle(Argument::allOf(
                Argument::type(SignUpCommand::class),
                Argument::that(function ($command) use ($email) {
                    if ($command->credentials->email->toString() !== $email) {
                        return false;
                    }

                    return true;
                })
            ))
            ->shouldBeCalledTimes(1);

        $response = $this->post($request);
        $response->getStatusCode()->shouldBe(Response::HTTP_OK);
    }

    public function it_should_render_email_already_exists_template(
        Twig\Environment $template,
        CommandBusInterface $commandBus,
        Request $request,
        ParameterBag $parameterBag
    ) {
        $request->request = $parameterBag;
        $email = 'toto@domain.com';
        $password = 'mypassword';

        $parameterBag->get('email')
            ->willReturn($email)->shouldBeCalledTimes(1);
        $parameterBag->get('password')
            ->willReturn($password)->shouldBeCalledTimes(1);

        $template->render(Argument::cetera())->shouldBeCalledTimes(1);

        $commandBus
            ->handle(Argument::type(SignUpCommand::class))
            ->willThrow(EmailAlreadyExistException::class)
            ->shouldBeCalledTimes(1);

        $response = $this->post($request);
        $response->getStatusCode()->shouldBe(Response::HTTP_CONFLICT);
    }

    public function it_should_render_register_form_if_error(
        Twig\Environment $template,
        CommandBusInterface $commandBus,
        Request $request,
        ParameterBag $parameterBag
    ) {
        $request->request = $parameterBag;
        $email = 'toto@domain.com';
        $password = 'mypassword';

        $parameterBag->get('email')
            ->willReturn($email)->shouldBeCalledTimes(1);
        $parameterBag->get('password')
            ->willReturn($password)->shouldBeCalledTimes(1);

        $template->render(Argument::cetera())->shouldBeCalledTimes(1);

        $commandBus
            ->handle(Argument::type(SignUpCommand::class))
            ->willThrow(\InvalidArgumentException::class)
            ->shouldBeCalledTimes(1);

        $response = $this->post($request);
        $response->getStatusCode()->shouldBe(Response::HTTP_BAD_REQUEST);
    }
}
