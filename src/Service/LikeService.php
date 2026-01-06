<?php

namespace App\Service;

use App\Entity\Event;
use App\Entity\EventLike;
use App\Repository\EventLikeRepository;
use Doctrine\ORM\EntityManagerInterface;

class LikeService
{
    public function __construct(
        private readonly EventLikeRepository $eventLikeRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly string $appSecret
    ) {
    }

    public function hashIp(string $ip): string
    {
        return hash('sha256', $this->appSecret . $ip);
    }

    public function hasLiked(Event $event, string $ip): bool
    {
        $ipHash = $this->hashIp($ip);
        return $this->eventLikeRepository->findByEventAndIpHash($event, $ipHash) !== null;
    }

    public function toggleLike(Event $event, string $ip): array
    {
        $ipHash = $this->hashIp($ip);
        $existingLike = $this->eventLikeRepository->findByEventAndIpHash($event, $ipHash);

        if ($existingLike) {
            $this->entityManager->remove($existingLike);
            $this->entityManager->flush();

            return [
                'liked' => false,
                'count' => $event->getLikesCount(),
            ];
        }

        $like = new EventLike();
        $like->setEvent($event);
        $like->setIpHash($ipHash);

        $this->entityManager->persist($like);
        $this->entityManager->flush();

        return [
            'liked' => true,
            'count' => $event->getLikesCount(),
        ];
    }
}
