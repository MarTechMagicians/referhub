<?php

namespace App\Domain\User;

use App\Domain\User\Entity\User;
use App\Domain\User\Exceptions\UserAlreadyExistsException;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function create(CreateUser $createUser): User
    {
        $currentUser = $this->userRepository->findOneBy([
            'identificationMethod' => $createUser->userIdentification->identificationMethod,
            'identificationValue' => $createUser->userIdentification->identificationValue,
        ]);
        if (null !== $currentUser) {
            throw new UserAlreadyExistsException();
        }

        $user = new User();
        $user->setIdentificationValue($createUser->userIdentification->identificationValue);
        $user->setIdentificationMethod($createUser->userIdentification->identificationMethod);
        $this->userRepository->save($user);

        return $user;
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param array<mixed>      $criteria
     * @param array<mixed>|null $orderBy
     */
    public function findOneBy(array $criteria, array $orderBy = null): ?User
    {
        return $this->userRepository->findOneBy($criteria, $orderBy);
    }
}
