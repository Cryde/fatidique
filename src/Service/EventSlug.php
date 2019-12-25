<?php

namespace App\Service;

use App\Repository\EventRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class EventSlug
{
    /**
     * @var EventRepository
     */
    private EventRepository $eventRepository;
    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;

    /**
     * EventSlug constructor.
     *
     * @param EventRepository  $eventRepository
     * @param SluggerInterface $slugger
     */
    public function __construct(EventRepository $eventRepository, SluggerInterface $slugger)
    {
        $this->eventRepository = $eventRepository;
        $this->slugger = $slugger;
    }

    /**
     * @param string $slugCandidate
     *
     * @return string
     */
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
