<?php

namespace App\Tests\Domain\User;

use App\Domain\User\Entity\User;
use App\Domain\User\UserRepository;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testCreateUserForExistedUser(): void
    {
        $this->expectException(UserAlreadyExistsException::class);

        $user = new User();
        $user
            ->setIdentificationMethod('email')
            ->setIdentificationValue('john.doe@test.com');

        $createUser = new CreateUser(identificationMethod: 'email', identificationValue: 'john.doe@test.com');

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();
        $userRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['identificationMethod' => $user->getIdentificationMethod(), 'identificationValue' => $user->getIdentificationValue()])
            ->willReturn($user);

        $userService = new UserService($userRepository);
        $userService->create($createUser);
    }

    public function testCreateUserSuccessfully(): void
    {
        $user = new User();
        $user
            ->setIdentificationMethod('email')
            ->setIdentificationValue('john.doe@test.com');

        $createUser = new CreateUser(identificationMethod: 'email', identificationValue: 'john.doe@test.com');

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();
        $userRepository
            ->expects($this->once())
            ->method('save')
            ->with($user);

        $userService = new UserService($userRepository);
        $actualUser = $userService->create($createUser);
        $this->assertEquals($user, $actualUser);
    }
}
