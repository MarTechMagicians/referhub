<?php

namespace App\Domain\User;

final readonly class UserIdentification
{
    public function __construct(
        public string $identificationMethod,
        public string $identificationValue
    ) {
    }
}
