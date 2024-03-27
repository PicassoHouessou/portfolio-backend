<?php

namespace App\Repository;

use App\Entity\ContactUs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactUs|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactUs|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactUs[]    findAll()
 * @method ContactUs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactUsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactUs::class);
    }

}
