<?php

namespace Romaind\PizzaStore\Infrastructure\Symfony5\Security;

use Romaind\PizzaStore\Infrastructure\Shared\Persistence\Doctrine\EntityRepository\UserEntityRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;

class LoginFormAuthentificator extends AbstractAuthenticator
{
    private UserEntityRepository $userRepository;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UserEntityRepository $userRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
    }

    public function supports(Request $request): ?bool
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * @throws AuthenticationException
     */
    public function authenticate(Request $request): PassportInterface
    {
        $user = $this->userRepository->findOneByUsername($request->request->get('email'));

        if (!$user) {
            throw new UsernameNotFoundException();
        }

        return new Passport($user, new PasswordCredentials($request->request->get('password')), [
            // and CSRF protection using a "csrf_token" field

            new CsrfTokenBadge('login_form', $request->request->get('csrf_token')),

            // and add support for upgrading the password hash
            //new PasswordUpgradeBadge($request->request->get('password'), $this->userRepository)
        ]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        dump('failer');
        exit;
        $response = new Response();

        return $response;
    }
}
