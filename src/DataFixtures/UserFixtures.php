<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    protected  $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('admin@yahoo.fr')
            ->setPassword($this->passwordHasher->hashPassword($user, 'admin'))
            ->setUsername('picasso')
            ->setCreatedAt(new \DateTime());
        $manager->persist($user);
        $manager->flush();
    }
}
