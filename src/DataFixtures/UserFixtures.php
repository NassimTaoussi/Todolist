<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }


    public function load(ObjectManager $manager): void
    {
        for ($count = 0; $count < 20; ++$count) {
            $user = new User();
            $user->setUsername('Username ' . $count);
            $user->setEmail('username' . $count . '@email.com');
            $user->setPassword($this->userPasswordHasher->hashPassword($user, '0000'));
            $user->setRole("ROLE_USER");
            $manager->persist($user);
        }

        for ($count = 0; $count < 5; ++$count) {
            $user = new User();
            $user->setUsername('Admin ' . $count);
            $user->setEmail('admin' . $count . '@email.com');
            $user->setPassword($this->userPasswordHasher->hashPassword($user, '0000'));
            $user->setRole("ROLE_ADMIN");
            $manager->persist($user);
        }

        $manager->flush();
    }
}