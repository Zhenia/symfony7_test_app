<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private ApiTokenRepository $repository
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $apiToken = $this->repository->findOneBy(['token' => $accessToken]);

        if (null === $apiToken) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        return new UserBadge($apiToken->getUser()->getUserIdentifier());
    }
}
