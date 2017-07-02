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
}