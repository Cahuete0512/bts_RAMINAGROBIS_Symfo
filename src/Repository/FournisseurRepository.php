<?php

namespace App\Repository;

use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Fournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fournisseur[]    findAll()
 * @method Fournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FournisseurRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

    /**
     * @param Fournisseur $entity
     * @param bool $flush
     */
    public function add(Fournisseur $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Fournisseur $entity
     * @param bool $flush
     */
    public function remove(Fournisseur $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param $sessionEnchere
     * @return int|mixed|string
     */
    public function findBySessionEnchere($sessionEnchere)
    {
        return $this->createQueryBuilder('f')
            ->join('f.sessionEnchereFournisseurs', 'sef')
            ->andWhere('sef.sessionEnchere = :sessionEnchere')
            ->setParameter('sessionEnchere', $sessionEnchere)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $cle
     * @return Fournisseur|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByCle($cle): ?Fournisseur
    {
        return $this->createQueryBuilder('f')
            ->join('f.sessionEnchereFournisseurs', 'sef')
            ->andWhere('sef.cleConnexion = :cle')
            ->setParameter('cle', $cle)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Fournisseur
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
