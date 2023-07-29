<?php

namespace App\Domain\User;

use App\Domain\User\Entity\User;

interface UserRepository
{
    public function save(User $user): void;
}
