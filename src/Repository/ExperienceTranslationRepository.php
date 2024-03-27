<?php

namespace App\Repository;

use App\Entity\ExperienceTranslation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExperienceTranslation>
 *
 * @method ExperienceTranslation|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExperienceTranslation|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExperienceTranslation[]    findAll()
 * @method ExperienceTranslation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExperienceTranslationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExperienceTranslation::class);
    }

}
