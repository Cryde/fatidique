<?php

namespace App\Service;

use App\Entity\Event;
use Hashids\HashidsInterface;

readonly class SlugRandomize
{
    public function __construct(private HashidsInterface $hashids)
    {
    }

    /**
     * @throws \Exception
     */
    public function randomizeSlug(Event $event): void
    {
        if ($event->isPrivate()) {
            $event->setSlug($this->hashids->encode(random_int(3000, 999999999999)));
        }
    }
}
