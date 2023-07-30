<?php

namespace App\Domain\Webhook;

use App\Domain\Referral\Entity\ReferralCode;
use App\Domain\Webhook\Entity\Webhook;

interface WebhookRepository
{
    public function save(Webhook $webhook): void;

    /**
     * @return Webhook[]
     */
    public function findByReferralCode(ReferralCode $referralCode): array;
}
