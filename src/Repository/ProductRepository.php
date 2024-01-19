<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
    public function findAllWithFds(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.fds', 'fds')
            ->addSelect('fds')
            ->getQuery()
            ->getResult();
    }

    public function findProductsForUser($user, $search = null)
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.users', 'u')
            ->where('u = :user')
            ->setParameter('user', $user);

        if ($search) {
            $qb->andWhere('p.name LIKE :search')
            ->setParameter('search', '%' . $search . '%');
        }

        return $qb->getQuery()->getResult();
    }

    // public function findProductsForUser(User $user): array
    // {
    //     return $this->createQueryBuilder('p')
    //         ->join('p.users', 'u')
    //         ->where('u = :user')
    //         ->setParameter('user', $user)
    //         ->getQuery()
    //         ->getResult();
    // }
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
