<?php

namespace App\Repository;

use App\Entity\UserTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\UserTranslation\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\UserTranslation\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\UserTranslation\UserInterface;

/**
 * @method UserTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserTranslation[]    findAll()
 * @method UserTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserTranslation::class);
    }

}
