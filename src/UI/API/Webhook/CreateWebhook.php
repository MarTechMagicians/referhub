<?php

namespace App\UI\API\Webhook;

final readonly class CreateWebhook
{
    /**
     * @param string[] $eventTypes
     */
    public function __construct(
        public string $referralCode,
        public string $url,
        public string $method,
        public array $eventTypes
    ) {
    }
}
