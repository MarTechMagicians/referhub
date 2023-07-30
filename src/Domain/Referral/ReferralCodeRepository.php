<?php

namespace App\Domain\Referral;

use App\Domain\Referral\Entity\ReferralCode;

interface ReferralCodeRepository
{
    public function save(ReferralCode $referralCode): void;

    /**
     * @param array<mixed>      $criteria
     * @param array<mixed>|null $orderBy
     *
     * @return ?ReferralCode
     */
    public function findOneBy(array $criteria, array $orderBy = null);
}
