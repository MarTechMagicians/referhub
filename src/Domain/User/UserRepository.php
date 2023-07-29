<?php

namespace App\Domain\User;

use App\Domain\User\Entity\User;

interface UserRepository
{
    public function save(User $user): void;

    /**
     * @param array<mixed>  $criteria
     * @param ?array<mixed> $orderBy
     *
     * @return ?User
     */
    public function findOneBy(array $criteria, array $orderBy = null);

    /**
     * @return User[]
     */
    public function findAll();
}
