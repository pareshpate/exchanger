<?php

namespace App\Repository;

use App\Entity\Rates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Rates|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rates|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rates[]    findAll()
 * @method Rates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rates::class);
    }
    
    public function findAll()
    {
        return $this->findBy(array(), array('id' => 'DESC'));
    }
}
