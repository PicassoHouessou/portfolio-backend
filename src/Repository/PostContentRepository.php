<?php

namespace App\Repository;

use App\Entity\PostContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PostContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostContent[]    findAll()
 * @method PostContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostContent::class);
    }

}
