<?php

namespace App\Tests\Domain\User;

use App\Domain\User\CreateUser;
use App\Domain\User\Entity\User;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\UserIdentification;
use App\Domain\User\UserRepository;
use App\Domain\User\UserService;
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

        $createUser = new CreateUser(new UserIdentification(identificationMethod: 'email', identificationValue: 'john.doe@test.com'));

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy', 'save'])
            ->getMockForAbstractClass();
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

        $createUser = new CreateUser(new UserIdentification(identificationMethod: 'email', identificationValue: 'john.doe@test.com'));

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save', 'findOneBy'])
            ->getMockForAbstractClass();
        $userRepository
            ->expects($this->once())
            ->method('save')
            ->with($user);

        $userService = new UserService($userRepository);
        $actualUser = $userService->create($createUser);
        $this->assertEquals($user, $actualUser);
    }

    public function testListUsersSuccessfully(): void
    {
        $user1 = new User();
        $user2 = new User();

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findAll'])
            ->getMockForAbstractClass();

        $userRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$user1, $user2]);

        $userService = new UserService($userRepository);
        $expectedResults = [$user1, $user2];
        $this->assertEquals($expectedResults, $userService->getAll());
    }

    public function testFindOneBy(): void
    {
        $user = new User();
        $user->setIdentificationMethod('email');
        $user->setIdentificationValue('john.doe@test.com');

        $userRepo = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMockForAbstractClass();

        $userRepo
            ->expects($this->once())
            ->method('findOneBy')
            ->willReturn($user);

        $userService = new UserService($userRepo);
        $this->assertEquals($user, $userService->findOneBy(['identificationMethod' => 'email', 'identificationValue' => 'john.doe@test.com']));
    }
}
