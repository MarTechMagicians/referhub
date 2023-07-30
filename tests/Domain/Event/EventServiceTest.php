<?php

namespace App\Tests\Domain\Event;

use App\Domain\Event\CreateEvent;
use App\Domain\Event\Entity\Event;
use App\Domain\Event\EventRepository;
use App\Domain\Event\EventService;
use App\Domain\Referral\Entity\ReferralCode;
use App\Domain\User\Entity\User;
use App\Domain\User\UserService;
use PHPUnit\Framework\TestCase;

class EventServiceTest extends TestCase
{
    public function testCreateSuccessfully(): void
    {
        $eventType = 'Sign Up';
        $referredUserEmail = 'referred@user.com';

        $referrerUser = new User();
        $referralCode = new ReferralCode();
        $referralCode->setCreatorUser($referrerUser);

        $createEvent = new CreateEvent(
            eventType: $eventType,
            referralCode: $referralCode,
            userIdentificationMethod: 'email',
            userIdentificationValue: $referredUserEmail
        );

        $expectedUser = new User();
        $expectedUser
            ->setIdentificationMethod('email')
            ->setIdentificationValue($referredUserEmail);

        $expectedEvent = new Event();
        $expectedEvent
            ->setEventType($eventType)
            ->setReferralCode($referralCode)
            ->setReferredUser($expectedUser)
            ->setCreatorUser($referralCode->getCreatorUser());

        $userService = $this->getMockBuilder(UserService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMock();
        $userService
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['identificationMethod' => 'email', 'identificationValue' => $referredUserEmail])
            ->willReturn($expectedUser);

        $eventRepository = $this->getMockBuilder(EventRepository::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $eventService = new EventService(userService: $userService, eventRepository: $eventRepository);
        $actualEvent = $eventService->createEvent($createEvent);

        $this->assertEquals($expectedEvent, $actualEvent);
    }
}
