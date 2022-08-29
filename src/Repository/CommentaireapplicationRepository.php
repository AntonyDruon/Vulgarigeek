<?php

namespace App\Repository;

use App\Entity\Commentaireapplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentaireapplication>
 *
 * @method Commentaireapplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaireapplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaireapplication[]    findAll()
 * @method Commentaireapplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireapplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaireapplication::class);
    }

    public function add(Commentaireapplication $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commentaireapplication $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Commentaireapplication[] Returns an array of Commentaireapplication objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commentaireapplication
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
