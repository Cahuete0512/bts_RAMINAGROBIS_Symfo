<?php

namespace App\Repository;

use App\Entity\SessionEnchere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SessionEnchere|null find($id, $lockMode = null, $lockVersion = null)
 * @method SessionEnchere|null findOneBy(array $criteria, array $orderBy = null)
 * @method SessionEnchere[]    findAll()
 * @method SessionEnchere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionEnchereRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SessionEnchere::class);
    }

    /**
     * @param SessionEnchere $entity
     * @param bool $flush
     */
    public function add(SessionEnchere $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param $date
     * @return SessionEnchere|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByDate($date): ?SessionEnchere
    {
        return $this->createQueryBuilder('enchere')
            ->andWhere('enchere.debutEnchere <= :debut')
            ->andWhere('enchere.finEnchere >= :fin')
            ->setParameter('debut', $date)
            ->setParameter('fin', $date)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param SessionEnchere $entity
     * @param bool $flush
     */
    public function remove(SessionEnchere $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return SessionEnchere[] Returns an array of SessionEnchere objects
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
    public function findOneBySomeField($value): ?SessionEnchere
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
