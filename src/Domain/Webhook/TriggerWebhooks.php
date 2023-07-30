<?php

namespace App\Domain\Webhook;

use App\Domain\Event\Entity\Event;

final readonly class TriggerWebhooks
{
    public function __construct(
        public Event $trackEvent
    ) {
    }
}
