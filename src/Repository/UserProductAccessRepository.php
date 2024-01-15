<?php

namespace App\Repository;

use App\Entity\UserProductAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserProductAccess>
 *
 * @method UserProductAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserProductAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserProductAccess[]    findAll()
 * @method UserProductAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserProductAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserProductAccess::class);
    }

//    /**
//     * @return UserProductAccess[] Returns an array of UserProductAccess objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserProductAccess
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
