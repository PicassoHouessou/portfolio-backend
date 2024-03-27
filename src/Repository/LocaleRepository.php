<?php

namespace App\Repository;

use App\Entity\Locale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Locale>
 *
 * @method Education|null find($id, $lockMode = null, $lockVersion = null)
 * @method Education|null findOneBy(array $criteria, array $orderBy = null)
 * @method Education[]    findAll()
 * @method Education[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class LocaleRepository extends ServiceEntityRepository
{

    private string $defaultLocale;
    private array $locales;

    public function __construct($defaultLocale, $locales, ManagerRegistry $registry)
    {
        $this->defaultLocale = $defaultLocale;
        $this->locales = $locales;

        parent::__construct($registry, Locale::class);
    }

    /**
     * Return defaultLocale code
     * @return string
     */
    public function getDefaultLocale()
    {
        $defaultLocale = $this->defaultLocale;
        $dbDefaultLocale = $this->findOneBy(array('isDefault'=>true));
        if($dbDefaultLocale){
            $defaultLocale = $dbDefaultLocale->getCode();
        }
        return $defaultLocale;
    }

    /**
     * Return array of availableLocale code
     * @return array
     */
    public function getAvailableLocales()
    {
        $qb = $this->createQueryBuilder('l');
        $qb->select('l.code AS locales');
        $result = $qb->getQuery()->getResult();
        $availableLocales = array_map(function($el){ return $el['locales']; }, $result);
        if(empty($availableLocales)){
            $availableLocales = $this->locales;
        }
        return $availableLocales;
    }
}