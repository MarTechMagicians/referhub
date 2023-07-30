<?php

namespace App\Domain\Referral;

class ReferralCodeGenerator
{
    public function __construct(
        private readonly string $appSecret
    ) {
    }

    public function generate(string $identificationMethod, string $identificationValue): string
    {
        $raw = $this->appSecret.$identificationMethod.$identificationValue;
        $hash = hash('sha256', $raw);

        return mb_strtoupper(substr($hash, 0, 10));
    }
}
