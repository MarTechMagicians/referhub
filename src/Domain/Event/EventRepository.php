<?php

namespace App\Domain\Event;

use App\Domain\Event\Entity\Event;

interface EventRepository
{
    public function save(Event $event): void;
}
