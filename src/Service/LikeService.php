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

    public function generateUsername(string $ip): string
    {
        $adjectives = [
            'Joyeux', 'Rigolo', 'Curieux', 'Malin', 'Rusé', 'Gentil', 'Doux', 'Brave',
            'Futé', 'Vif', 'Calme', 'Sage', 'Fier', 'Grand', 'Petit', 'Cool',
            'Rapide', 'Agile', 'Discret', 'Magique', 'Brillant', 'Timide', 'Rêveur', 'Zen'
        ];
        $nouns = [
            'Panda', 'Koala', 'Lion', 'Tigre', 'Ours', 'Loup', 'Renard', 'Chat',
            'Lapin', 'Hibou', 'Aigle', 'Dauphin', 'Pingouin', 'Licorne', 'Dragon',
            'Montagne', 'Océan', 'Soleil', 'Lune', 'Étoile', 'Nuage', 'Forêt', 'Rivière',
            'Colibri', 'Papillon', 'Phoenix', 'Aurore', 'Cascade', 'Volcan', 'Corail', 'Bambou'
        ];

        $hash = $this->hashIp($ip);
        $adjIndex = hexdec(substr($hash, 0, 2)) % count($adjectives);
        $nounIndex = hexdec(substr($hash, 2, 2)) % count($nouns);
        $number = hexdec(substr($hash, 4, 2)) % 1000;

        return $nouns[$nounIndex] . $adjectives[$adjIndex] . $number;
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
