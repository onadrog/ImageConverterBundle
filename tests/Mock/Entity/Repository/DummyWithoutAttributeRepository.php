<?php

namespace Onadrog\ImageConverterBundle\Mock\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\DummyWithoutAttribute;

/**
 * @method DummyWithoutAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method DummyWithoutAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method DummyWithoutAttribute[]    findAll()
 * @method DummyWithoutAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DummyWithoutAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DummyWithoutAttribute::class);
    }

    // /**
    //  * @return DummyWithoutAttribute[] Returns an array of DummyWithoutAttribute objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DummyWithoutAttribute
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
