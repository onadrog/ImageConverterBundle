<?php

namespace Onadrog\ImageConverterBundle\Mock\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\DummyWithAttribute;

/**
 * @method DummyWithAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method DummyWithAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method DummyWithAttribute[]    findAll()
 * @method DummyWithAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DummyWithAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DummyWithAttribute::class);
    }

    // /**
    //  * @return DummyWithAttribute[] Returns an array of DummyWithAttribute objects
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
    public function findOneBySomeField($value): ?DummyWithAttribute
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
