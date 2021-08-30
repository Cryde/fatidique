<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findLastPublicEvents(int $limit = 5): array
    {
        return $this->createQueryBuilder('event')
            ->orderBy('event.created', 'DESC')
            ->where('event.private = 0')
            ->andWhere('event.date > CURRENT_DATE()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findAlmostEndedPublicEvents(int $limit = 5): array
    {
        return $this->createQueryBuilder('event')
            ->orderBy('event.date', 'ASC')
            ->where('event.private = 0')
            ->andWhere('event.date > CURRENT_DATE()')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
