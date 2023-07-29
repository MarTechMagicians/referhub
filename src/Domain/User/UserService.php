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
            'identificationMethod' => $createUser->identificationMethod,
            'identificationValue' => $createUser->identificationValue,
        ]);
        if (null !== $currentUser) {
            throw new UserAlreadyExistsException();
        }

        $user = new User();
        $user->setIdentificationValue($createUser->identificationValue);
        $user->setIdentificationMethod($createUser->identificationMethod);
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
}
