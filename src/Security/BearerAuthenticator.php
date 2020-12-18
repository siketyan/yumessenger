<?php

declare(strict_types=1);

namespace App\Security;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class BearerAuthenticator extends AbstractAuthenticator
{
    private const AUTHORIZATION_HEADER = 'Authorization';
    private const AUTHORIZATION_HEADER_PREFIX = 'Bearer ';

    private SessionStorage $sessionStorage;

    public function __construct(
        SessionStorage $sessionStorage,
    ) {
        $this->sessionStorage = $sessionStorage;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has(self::AUTHORIZATION_HEADER)
            && str_starts_with(
                $request->headers->get(self::AUTHORIZATION_HEADER),
                self::AUTHORIZATION_HEADER_PREFIX,
            )
        ;
    }

    /**
     * @throws InvalidArgumentException
     *
     * @inheritDoc
     */
    public function authenticate(Request $request): PassportInterface
    {
        $token = substr(
            $request->headers->get(self::AUTHORIZATION_HEADER),
            strlen(self::AUTHORIZATION_HEADER_PREFIX),
        );

        $session = $this->sessionStorage->load($token);

        if ($session === null) {
            throw new BadCredentialsException();
        }

        return new SelfValidatingPassport(
            new UserBadge($session->getUser()->getId()),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(
            ['message' => strtr($exception->getMessageKey(), $exception->getMessageData())],
            Response::HTTP_UNAUTHORIZED,
        );
    }
}
