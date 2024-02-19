<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $plaintextPassword = '54321';
        $hashedPassword = $this->passwordHasher->hashPassword($userAdmin, $plaintextPassword);
        $userAdmin->setPassword($hashedPassword);
        $manager->persist($userAdmin);
        $manager->flush();

        $userSupperAdmin = new User();
        $userSupperAdmin->setUsername('superadmin');
        $userSupperAdmin->setRoles(['ROLE_SUPER_ADMIN']);
        $plaintextPassword = '12345';
        $hashedPassword = $this->passwordHasher->hashPassword($userAdmin, $plaintextPassword);
        $userSupperAdmin->setPassword($hashedPassword);
        $manager->persist($userSupperAdmin);
        $manager->flush();
    }
}
