<?php

namespace AppBundle\Service;

use AppBundle\Entity\Event;
use kotchuprik\short_id\ShortId;

class SlugRandomize
{
    /**
     * @var ShortId
     */
    private $shortId;

    public function __construct(ShortId $shortId)
    {
        $this->shortId = $shortId;
    }

    /**
     * @var Event $event
     */
    private $event;

    public function setEvent(Event $event)
    {
        $this->event = $event;

        return $this;
    }

    public function randomizeSlug()
    {
        if ($this->event->isPrivate()) {
            $this->event->setSlug($this->shortId->encode(random_int(3000, 99999999), 9));
        }
    }
}
