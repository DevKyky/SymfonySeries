<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function add(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBestSeries(int $page) {
        // DQL
/*        $entityManager = $this->getEntityManager();
        $dql = "SELECT s
                FROM App\Entity\Serie s
                WHERE s.popularity > 500
                AND s.vote > 7
                ORDER BY s.popularity DESC";

        $query = $entityManager->createQuery($dql);
        $query->setMaxResults(50);
        return $query->getResult();*/

        $limit = 50;
        $offset = (($page - 1) * $limit);

        // QueryBuilder
        $qb = $this->createQueryBuilder('s');
        $qb->leftJoin("s.seasons", "seasons")
            ->addSelect('seasons')
//        $qb->andWhere("s.popularity > 100")
//            ->andWhere("s.vote > :vote")
            ->addOrderBy("s.popularity", "DESC");

//        $qb->setParameter('vote', $vote);

        $query =$qb->getQuery();
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);

        $paginator = new Paginator($query);

        return $paginator;
    }
}
