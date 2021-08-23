<?php

namespace Onadrog\ImageConverterBundle\Mock\Entity\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Onadrog\ImageConverterBundle\Mock\Entity\Entity\SoloFile;

/**
 * @method SoloFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method SoloFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method SoloFile[]    findAll()
 * @method SoloFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoloFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SoloFile::class);
    }

    // /**
    //  * @return SoloFile[] Returns an array of SoloFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SoloFile
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
