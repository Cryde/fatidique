<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
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
