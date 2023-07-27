<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Security;

final class UserManager
{

    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function add(User $user) {
        //dump($user);
        //exit();
        $user->setUsername($user->getUsername());
        $user->setEmail($user->getEmail());
        $user->setPassword($user->getUsername());
        $user->setRole($user->getRole());


        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function update(User $user) {


        $this->entityManager->flush();
    }

}
