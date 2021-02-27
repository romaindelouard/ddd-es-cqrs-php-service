<?php

declare(strict_types=1);

namespace tests\Unit\Romaind\PizzaStore\Infrastructure\User\Authentication\Guard;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Romaind\PizzaStore\Application\Command\User\SignIn\SignInCommand;
use Romaind\PizzaStore\Application\Query\Authentication\GetUserByEmail\GetUserByEmailQuery;
use Romaind\PizzaStore\Domain\Model\User\Exception\InvalidCredentialsException;
use Romaind\PizzaStore\Infrastructure\Shared\Bus\Command\MessengerCommandBus;
use Romaind\PizzaStore\Infrastructure\Shared\Bus\Query\MessengerQueryBus;
use Romaind\PizzaStore\Infrastructure\User\Authentication\Guard\LoginAuthenticator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginAuthenticatorSpec extends ObjectBehavior
{
    public function let(
        MessengerCommandBus $commandBus,
        MessengerQueryBus $queryBus,
        UrlGeneratorInterface $router
    ) {
        $this->beConstructedWith($commandBus, $queryBus, $router);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(LoginAuthenticator::class);
        $this->shouldBeAnInstanceOf(AbstractFormLoginAuthenticator::class);
    }

    public function it_should_return_true_on_available_request(
        Request $request,
        UrlGeneratorInterface $router
    ) {
        $router->generate('login')->willReturn('/login');
        $request->getPathInfo()->willReturn('/login');
        $request->isMethod('POST')->willReturn(true)->shouldBeCalledTimes(1);

        $this->supports($request)->shouldBe(true);
    }

    public function it_should_get_credentials(
        Request $request,
        ParameterBag $parameterBag
    ) {
        $email = 'toto@domain.com';
        $password = 'mypassword';
        $request->request = $parameterBag;
        $parameterBag->get('_email')
            ->willReturn($email)->shouldBeCalledTimes(1);
        $parameterBag->get('_password')
            ->willReturn($password)->shouldBeCalledTimes(1);

        $this->getCredentials($request)->shouldBe([
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function it_should_return_an_user_on_the_credentials(
        UserProviderInterface $userProvider,
        MessengerQueryBus $queryBus,
        MessengerCommandBus $commandBus
    ) {
        $email = 'toto@domain.com';
        $password = 'mypassword';
        $credentials = ['email' => $email, 'password' => $password];

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

        $queryBus
            ->ask(Argument::allOf(
                Argument::type(GetUserByEmailQuery::class),
                Argument::that(function ($command) use ($email) {
                    if ($command->email->toString() !== $email) {
                        return false;
                    }

                    return true;
                }
            )))
            ->shouldBeCalledTimes(1);

        $this->getUser($credentials, $userProvider);
    }

    public function it_should_throw_an_authentication_exception_on_invalid_credentials(
        UserProviderInterface $userProvider,
        MessengerQueryBus $queryBus,
        MessengerCommandBus $commandBus
    ) {
        $email = 'toto@domain.com';
        $password = 'mypassword';
        $credentials = ['email' => $email, 'password' => $password];

        $commandBus
            ->handle(Argument::type(SignInCommand::class))
            ->willThrow(InvalidCredentialsException::class)
            ->shouldBeCalledTimes(1);

        $queryBus->ask(Argument::any())->shouldNotBeCalled();

        $this
            ->shouldThrow(AuthenticationException::class)
            ->during('getUser', [$credentials, $userProvider]);
    }

    public function it_should_throw_an_authentication_exception_on_invalid_email(
        UserProviderInterface $userProvider,
        MessengerQueryBus $queryBus,
        MessengerCommandBus $commandBus
    ) {
        $email = 'toto@domain.com@not.valid';
        $password = 'mypassword';
        $credentials = ['email' => $email, 'password' => $password];

        $commandBus
            ->handle(Argument::type(SignInCommand::class))
            ->shouldNotBeCalled();

        $queryBus->ask(Argument::any())->shouldNotBeCalled();

        $this
            ->shouldThrow(AuthenticationException::class)
            ->during('getUser', [$credentials, $userProvider]);
    }

    public function it_should_return_true_on_check_credentials(
        UserInterface $user
    ) {
        $this->checkCredentials([], $user)->shouldBe(true);
    }

    public function it_should_redirect_on_authentication_success(
        Request $request,
        TokenInterface $token,
        UrlGeneratorInterface $router
    ) {
        $router->generate('profile')->willReturn('/profile')->shouldBeCalledTimes(1);

        $response = $this->onAuthenticationSuccess($request, $token, 'providerKey');
        $response->shouldHaveType(RedirectResponse::class);
    }
}
