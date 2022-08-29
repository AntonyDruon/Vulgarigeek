<?php

namespace App\Repository;

use App\Entity\Commentairelogiciel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commentairelogiciel>
 *
 * @method Commentairelogiciel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentairelogiciel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentairelogiciel[]    findAll()
 * @method Commentairelogiciel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentairelogicielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentairelogiciel::class);
    }

    public function add(Commentairelogiciel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commentairelogiciel $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Commentairelogiciel[] Returns an array of Commentairelogiciel objects
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

//    public function findOneBySomeField($value): ?Commentairelogiciel
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
