<?php

namespace App\Domain\User;

final readonly class CreateUser
{
    public function __construct(
        public string $identificationMethod,
        public string $identificationValue
    ) {
    }
}
