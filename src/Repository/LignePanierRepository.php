<?php

namespace App\Repository;

use App\Entity\LignePanier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @method LignePanier|null find($id, $lockMode = null, $lockVersion = null)
 * @method LignePanier|null findOneBy(array $criteria, array $orderBy = null)
 * @method LignePanier[]    findAll()
 * @method LignePanier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LignePanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LignePanier::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(LignePanier $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(LignePanier $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return LignePanier[] Returns an array of LignePanier objects
     */
    public function findByFournisseur($fournisseur)
    {
        return $this->createQueryBuilder('lp')
            ->addSelect(['e'])
            ->join('lp.fournisseurs', 'f')
            ->join('f.sessionEnchereFournisseurs', 'sef')
            ->join('sef.sessionEnchere', 'se')
            ->leftJoin('lp.encheres', 'e', Expr\Join::WITH, 'e.fournisseur = :fournisseur')
            ->andWhere('f = :fournisseur')
            ->andWhere('se.debutEnchere <= :now')
            ->andWhere('se.finEnchere >= :now')
            ->setParameter('fournisseur', $fournisseur)
            ->setParameter('now', new \DateTime())
            ->orderBy('lp.reference', 'ASC')
            ->addOrderBy('e.prixEnchere', 'DESC')
            ->getQuery()
            ->setFetchMode('LignePanier', 'encheres', \Doctrine\ORM\Mapping\ClassMetadata::FETCH_EAGER)
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?LignePanier
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
