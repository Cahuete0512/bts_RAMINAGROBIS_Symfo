<?php

namespace App\Repository;

use App\Entity\Enchere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Enchere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enchere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enchere[]    findAll()
 * @method Enchere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnchereRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enchere::class);
    }

    /**
     * @param Enchere $entity
     * @param bool $flush
     */
    public function add(Enchere $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Enchere $entity
     * @param bool $flush
     */
    public function remove(Enchere $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function updateStatut(): void
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'update enchere set position = -1';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
        $this->_em->flush();

        $sql = 'update enchere set position = 1 where id in (
                        select e1.id
                        from enchere e1
                        where prix_enchere = (select max(prix_enchere) from enchere e2 where e2.ligne_panier_id = e1.ligne_panier_id)
                    )';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
        $this->_em->flush();

        $sql = 'update enchere set position = 0 where id in (
                        select e1.id
                        from enchere e1
                        where prix_enchere = (select max(prix_enchere) from enchere e2 where e2.ligne_panier_id = e1.ligne_panier_id)
                          and (select count(*)
                               from enchere e3
                               where e3.ligne_panier_id = e1.ligne_panier_id
                                 and prix_enchere = (
                                   select max(prix_enchere)
                                   from enchere e2
                                   where e2.ligne_panier_id = e1.ligne_panier_id)) > 1
                        )';
        $stmt = $conn->prepare($sql);
        $stmt->executeQuery();
        $this->_em->flush();
    }

    // /**
    //  * @return Enchere[] Returns an array of Enchere objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Enchere
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
