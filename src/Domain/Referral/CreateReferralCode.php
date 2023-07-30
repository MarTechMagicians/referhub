<?php

namespace App\Domain\Referral;

use App\Domain\User\UserIdentification;

final readonly class CreateReferralCode
{
    public function __construct(
        public UserIdentification $userIdentification
    ) {
    }
}
