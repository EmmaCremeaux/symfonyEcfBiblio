<?php

namespace App\Repository;

use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 *
 * @method Emprunt|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunt|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunt[]    findAll()
 * @method Emprunt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }


    /**
     * This method find emprunts
     * @return Emprunt[] Returns an array of 10 emprunt objects order by date
    */
    public function findByDate(): array
    {
        return $this->createQueryBuilder('et')
            ->orderBy('et.dateEmprunt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * This method find emprunts which have the emprunteur_id 2
     * @return Emprunt[] Returns an array of emprunt objects order by date
    */
    public function findByEmprunteur($emprunteur): array
    {
        return $this->createQueryBuilder('e')
            ->innerJoin('e. emprunteur', 'emp')
            ->andWhere('emp = :emprunteur')
            ->setParameter('emprunteur', $emprunteur)
            ->orderBy('e.dateEmprunt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * This method find emprunts which have the emprunteur_id 2
     * @return Emprunt[] Returns an array of emprunt objects order by date
    */
    public function findByLivre(?int $livreId) : array
    {
        return $this->createQueryBuilder('et')
            ->andWhere('et.livre = :val')
            ->setParameter('val', $livreId)
            ->orderBy('et.dateEmprunt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * This method find emprunts
     * @return Emprunt[] Returns an array of 10 emprunt objects order by return Date
    */
    public function findByRetour(): array
    {
        return $this->createQueryBuilder('et')
            ->andWhere('et.dateRetour IS NOT NULL')
            ->orderBy('et.dateRetour', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * This method find emprunts not return
     * @return Emprunt[] Returns an array of emprunt objects
    */
    public function findByNotRetour(): array
    {
        return $this->createQueryBuilder('et')
            ->andWhere('et.dateRetour IS NULL')
            ->orderBy('et.dateEmprunt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


//    /**
//     * @return Emprunt[] Returns an array of Emprunt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Emprunt
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
