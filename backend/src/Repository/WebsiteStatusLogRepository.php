<?php

namespace App\Repository;

use App\Entity\WebsiteStatusLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WebsiteStatusLog>
 */
class WebsiteStatusLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebsiteStatusLog::class);
    }
    public function deleteAllOlderThan($deleteDate): int
    {
        $qb = $this->createQueryBuilder('s')
            ->delete()
            ->where('s.checkedAt < :deleteDate')
            ->setParameter(':deleteDate', $deleteDate)
            ->getQuery();

        return $qb->execute();
    }
    public function getStatusLogs($websiteId): array
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.websiteId = :websiteId')
            ->setParameter(':websiteId', $websiteId)
            ->orderBy('s.checkedAt', 'DESC')
            ->getQuery();

        return $qb->getArrayResult();
    }
    //    /**
    //     * @return WebsiteStatusLog[] Returns an array of WebsiteStatusLog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('w.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?WebsiteStatusLog
    //    {
    //        return $this->createQueryBuilder('w')
    //            ->andWhere('w.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
