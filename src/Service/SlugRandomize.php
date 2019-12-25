<?php

namespace App\Service;

use App\Entity\Event;
use kotchuprik\short_id\ShortId;

class SlugRandomize
{
    /**
     * @var ShortId
     */
    private ShortId $shortId;

    /**
     * SlugRandomize constructor.
     *
     * @param ShortId $shortId
     */
    public function __construct(ShortId $shortId)
    {
        $this->shortId = $shortId;
    }

    /**
     * @param Event $event
     *
     * @throws \Exception
     */
    public function randomizeSlug(Event $event): void
    {
        if ($event->isPrivate()) {
            $event->setSlug($this->shortId->encode(random_int(3000, 99999999), 9));
        }
    }
}
