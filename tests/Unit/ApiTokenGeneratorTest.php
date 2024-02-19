<?php

namespace Unit;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Service\ApiTokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ApiTokenGeneratorTest extends TestCase
{

    public function testApiToken(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $user = new User();
        $user->setUsername('test');

        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(ApiToken::class));

        $entityManager->expects($this->once())
            ->method('flush');

        $apiTokenGenerator = new ApiTokenGenerator($entityManager);

        $token = $apiTokenGenerator->create($user);

        $this->assertNotEmpty($token);
    }
}
