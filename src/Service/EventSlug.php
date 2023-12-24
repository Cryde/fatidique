<?php

namespace App\Service;

use App\Repository\EventRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class EventSlug
{
    public function __construct(
        private readonly EventRepository  $eventRepository,
        private readonly SluggerInterface $slugger
    ) {
    }

    public function create(string $slugCandidate): string
    {
        $slug = $this->slugger->slug($slugCandidate)->lower();
        $i = 1;
        $initialSlug = $slug;
        while ($this->eventRepository->count(['slug' => $slug]) > 0) {
            $slug = $initialSlug . '-' . $i++;
        }

        return $slug;
    }
}
