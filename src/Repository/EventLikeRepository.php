<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\EventLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventLike>
 *
 * @method EventLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventLike[]    findAll()
 * @method EventLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventLike::class);
    }

    public function findByEventAndIpHash(Event $event, string $ipHash): ?EventLike
    {
        return $this->findOneBy([
            'event' => $event,
            'ipHash' => $ipHash,
        ]);
    }

    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
