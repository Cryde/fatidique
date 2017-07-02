<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository
{
    public function findLastPublicEvents(int $limit = 5)
    {
        return $this->createQueryBuilder('event')
                    ->orderBy('event.created', 'DESC')
                    ->where('event.private = 0')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }

    public function findAlmostEndedPublicEvents(int $limit = 5)
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