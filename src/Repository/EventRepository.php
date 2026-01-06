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

    public function findMostLikedPublicEvents(int $limit = 5): array
    {
        return $this->createQueryBuilder('event')
            ->leftJoin('event.likes', 'likes')
            ->where('event.private = 0')
            ->andWhere('event.date > CURRENT_DATE()')
            ->groupBy('event.id')
            ->having('COUNT(likes.id) > 0')
            ->orderBy('COUNT(likes.id)', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countAll(): int
    {
        return (int) $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countEndingToday(): int
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        return (int) $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->where('e.date >= :today')
            ->andWhere('e.date < :tomorrow')
            ->andWhere('e.private = 0')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findMostPopularTheme(): ?string
    {
        $result = $this->createQueryBuilder('e')
            ->select('e.theme, COUNT(e.id) as cnt')
            ->where('e.theme IS NOT NULL')
            ->groupBy('e.theme')
            ->orderBy('cnt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $result ? $result['theme'] : 'default';
    }

    public function getThemeDistribution(): array
    {
        $results = $this->createQueryBuilder('e')
            ->select('COALESCE(e.theme, \'default\') as theme, COUNT(e.id) as count')
            ->groupBy('e.theme')
            ->orderBy('count', 'DESC')
            ->getQuery()
            ->getResult();

        $distribution = [];
        foreach ($results as $row) {
            $distribution[$row['theme'] ?? 'default'] = (int) $row['count'];
        }

        return $distribution;
    }

    public function getAverageCountdownDays(): float
    {
        $events = $this->createQueryBuilder('e')
            ->select('e.created, e.date')
            ->where('e.date > e.created')
            ->getQuery()
            ->getResult();

        if (empty($events)) {
            return 0;
        }

        $totalDays = 0;
        foreach ($events as $event) {
            $diff = $event['date']->diff($event['created']);
            $totalDays += $diff->days;
        }

        return round($totalDays / count($events), 1);
    }
}
