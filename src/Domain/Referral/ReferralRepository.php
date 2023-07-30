<?php

namespace App\Domain\Referral;

use App\Domain\Referral\Entity\Referral;

interface ReferralRepository
{
    public function save(Referral $referral): void;
}
