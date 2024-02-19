<?php

namespace App\Service;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ApiTokenGenerator
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function create(User $user)
    {
        $token = new ApiToken();
        $token->setToken(bin2hex(random_bytes(20)));
        $token->setUser($user);
        $this->em->persist($token);
        $this->em->flush();

        return $token->getToken();
    }
}
