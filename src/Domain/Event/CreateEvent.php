<?php

namespace App\Domain\Event;

use App\Domain\Referral\Entity\ReferralCode;

final readonly class CreateEvent
{
    public function __construct(
        public string $eventType,
        public ReferralCode $referralCode,
        public string $userIdentificationMethod,
        public string $userIdentificationValue
    ) {
    }
}
