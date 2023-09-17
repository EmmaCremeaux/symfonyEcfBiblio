<?php

namespace App\Repository;

use DateTime;
use App\Entity\Emprunteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunteur>
 *
 * @method Emprunteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Emprunteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Emprunteur[]    findAll()
 * @method Emprunteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmprunteurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunteur::class);
    }

    /**
    * @return Emprunteur[] Returns an array of Emprunteur objects
    */
   public function findAll(): array
   {
       return $this->createQueryBuilder('e')
            ->orderBy('e.nom', 'ASC')
            ->addOrderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult()
       ;
   }

       /**
     * This method finds emprunteur with a user
     * @param string $userId The id of the user of the emprunteur for which we want to find the emprunteurs
     * @return Emprunteur[] Returns an array to Emprunteur objects
    */
    public function findByUser(?int $userId) : array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.user = :val')
            ->setParameter('val', $userId)
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * This method find all emprunteur containing one word
     * @param string $keyword The word to search for
     * @return Emprunteur[] Returns an array of emprunteur objects
    */
    public function findEmprunteurByKeyword(?string $keyword): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.nom LIKE :val')
            ->orWhere('e.prenom LIKE :val')
            ->setParameter('val', "%$keyword%")
            ->orderBy('e.nom', 'ASC')
            ->addOrderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * This method find all emprunteur containing one word
     * @param string $keyword The word to search for
     * @return Emprunteur[] Returns an array of emprunteur objects
    */
    public function findEmprunteurByTel(?int $tel): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.telephone LIKE :val')
            ->setParameter('val', "%$tel%")
            ->orderBy('e.nom', 'ASC')
            ->addOrderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * This method find all emprunteur created before one date
     * @param DateTime $date The word to search for
     * @return Emprunteur[] Returns an array of emprunteur objects
    */
    public function findByCreatedDate(?DateTime $date): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.createdAt < :val')
            ->setParameter('val', $date)
            ->orderBy('e.nom', 'ASC')
            ->addOrderBy('e.prenom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    
//    /**
//     * @return Emprunteur[] Returns an array of Emprunteur objects
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

//    public function findOneBySomeField($value): ?Emprunteur
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
