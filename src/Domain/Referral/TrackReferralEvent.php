<?php

namespace App\Domain\Referral;

use App\Domain\User\UserIdentification;

final readonly class TrackReferralEvent
{
    public function __construct(
        public string $eventType,
        public string $referralCode,
        public UserIdentification $userIdentification
    ) {
    }
}
