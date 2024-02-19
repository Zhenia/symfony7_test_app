<?php

namespace App\Tests\Integration;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTest extends KernelTestCase
{
    private Container $container;
    private EntityManagerInterface $em;
    protected function setUp(): void
    {
        self::bootKernel();
        $this->container = self::getContainer();
        $this->em = $this->container->get('doctrine')->getManager();
    }

    public function testCreateUser(): void
    {
        /** @var UserPasswordHasherInterface $passwordHasher */
        $passwordHasher = $this->container->get('security.user_password_hasher');

        $newUser = new User();
        $newUser->setUsername('newuser');
        $newUser->setRoles(['ROLE_ADMIN']);
        $newUser->setPassword($passwordHasher->hashPassword($newUser, 'password'));

        $this->em->persist($newUser);
        $this->em->flush();

        $userRepository = $this->em->getRepository(User::class);
        $newUserFromDB = $userRepository->findOneBy(['username' => 'newuser']);

        $this->assertInstanceOf(User::class, $newUserFromDB);

    }
}
