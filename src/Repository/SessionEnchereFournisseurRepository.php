<?php

namespace App\Repository;

use App\Entity\SessionEnchereFournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SessionEnchereFournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionEnchereFournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionEnchereFournisseur[]    findAll()
 * @method SessionEnchereFournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionEnchereFournisseurRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionEnchereFournisseur::class);
    }

    /**
     * @param SessionEnchereFournisseur $entity
     * @param bool $flush
     */
    public function add(SessionEnchereFournisseur $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param SessionEnchereFournisseur $entity
     * @param bool $flush
     */
    public function remove(SessionEnchereFournisseur $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return SessionEnchereFournisseur[] Returns an array of SessionEnchereFournisseur objects
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
    public function findOneBySomeField($value): ?SessionEnchereFournisseur
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
