<?php

namespace App\Domain\Event;

use App\Domain\Referral\Entity\ReferralCode;
use App\Domain\User\UserIdentification;

final readonly class CreateEvent
{
    public function __construct(
        public string $eventType,
        public ReferralCode $referralCode,
        public UserIdentification $userIdentification
    ) {
    }
}
