<?php

namespace App\Domain\Webhook;

use App\Domain\Referral\Entity\ReferralCode;

final readonly class CreateWebhook
{
    /**
     * @param string[] $eventTypes
     */
    public function __construct(
        public ReferralCode $referralCode,
        public string $url,
        public string $method,
        public array $eventTypes
    ) {
    }
}
