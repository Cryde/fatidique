<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    /**
     * @param int $limit
     *
     * @return array
     */
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

    /**
     * @param int $limit
     *
     * @return array
     */
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
