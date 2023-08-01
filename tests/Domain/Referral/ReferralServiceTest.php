<?php

namespace App\Tests\Domain\Referral;

use App\Domain\Event\CreateEvent;
use App\Domain\Event\Entity\Event;
use App\Domain\Event\EventService;
use App\Domain\Referral\CreateReferralCode;
use App\Domain\Referral\Entity\Referral;
use App\Domain\Referral\Entity\ReferralCode;
use App\Domain\Referral\Exceptions\InvalidReferralCode;
use App\Domain\Referral\ReferralCodeGenerator;
use App\Domain\Referral\ReferralCodeRepository;
use App\Domain\Referral\ReferralRepository;
use App\Domain\Referral\ReferralService;
use App\Domain\Referral\TrackReferralEvent;
use App\Domain\User\CreateUser;
use App\Domain\User\Entity\User;
use App\Domain\User\UserIdentification;
use App\Domain\User\UserService;
use App\Domain\Webhook\TriggerWebhooks;
use App\Domain\Webhook\WebhookService;
use PHPUnit\Framework\TestCase;

class ReferralServiceTest extends TestCase
{
    public function testCreateReferralCodeSuccessfully(): void
    {
        $createReferralCode = new CreateReferralCode(
            new UserIdentification(
                identificationMethod: 'email',
                identificationValue: 'john.doe@test.com'
            )
        );

        $expectedUser = new User();
        $expectedUser
            ->setIdentificationMethod($createReferralCode->userIdentification->identificationMethod)
            ->setIdentificationValue($createReferralCode->userIdentification->identificationValue);

        $userService = $this->getMockBuilder(UserService::class)
            ->disableOriginalConstructor()
            ->getMock();
        $userService
            ->expects($this->once())
            ->method('findOrCreate')
            ->with(new CreateUser($createReferralCode->userIdentification))
            ->willReturn($expectedUser);

        $expectedReferralCode = new ReferralCode();
        $expectedReferralCode
            ->setCreatorUser($expectedUser);

        $referralCodeValue = '123';
        $referralCodeGenerator = $this->getMockBuilder(ReferralCodeGenerator::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['generate'])
            ->getMock();
        $referralCodeGenerator
            ->expects($this->once())
            ->method('generate')
            ->willReturn($referralCodeValue);
        $expectedReferralCode->setCode($referralCodeValue);

        $referralService = new ReferralService(
            userService: $userService,
            referralCodeGenerator: $referralCodeGenerator,
            referralCodeRepository: $this->createMock(ReferralCodeRepository::class),
            eventService: $this->createMock(EventService::class),
            referralRepository: $this->createMock(ReferralRepository::class),
            webhookService: $this->createMock(WebhookService::class)
        );
        $referralCode = $referralService->createReferralCode($createReferralCode);

        $this->assertEquals($expectedReferralCode, $referralCode);
    }

    public function testSuccessfulReferralTracking(): void
    {
        $referralCode = '123';
        $eventType = 'Sign Up';
        $identificationMethod = 'email';
        $identificationValue = 'referred.user@test.com';

        $referredUser = new User();
        $referralUser = new User();

        $userService = $this->getMockBuilder(UserService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMockForAbstractClass();
        $userIdentification = new UserIdentification(
            identificationMethod: $identificationMethod,
            identificationValue: $identificationValue
        );
        $userService
            ->expects($this->once())
            ->method('create')
            ->with(new CreateUser(userIdentification: $userIdentification))
            ->willReturn($referredUser);

        $referralCodeEntity = new ReferralCode();
        $referralCodeEntity
            ->setCreatorUser($referralUser)
            ->setCode($referralCode);

        $referralCodeRepository = $this->getMockBuilder(ReferralCodeRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findOneBy'])
            ->getMockForAbstractClass();
        $referralCodeRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['code' => $referralCode])
            ->willReturn($referralCodeEntity);

        $expectedEvent = new Event();
        $expectedEvent
            ->setReferralCode($referralCodeEntity)
            ->setEventType($eventType)
            ->setCreatorUser($referralCodeEntity->getCreatorUser())
            ->setReferredUser($referredUser);

        $eventService = $this->getMockBuilder(EventService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['createEvent'])
            ->getMock();
        $eventService
            ->expects($this->once())
            ->method('createEvent')
            ->with(new CreateEvent(
                eventType: $eventType,
                referralCode: $referralCodeEntity,
                userIdentification: $userIdentification
            ))
            ->willReturn($expectedEvent);

        $trackEvent = new TrackReferralEvent(
            eventType: $eventType,
            referralCode: $referralCode,
            userIdentification: $userIdentification
        );

        $expectedReferral = new Referral();
        $expectedReferral
            ->setReferralCode($referralCodeEntity)
            ->setCreatorUser($referralUser)
            ->setReferredUser($referredUser);

        $referralRepo = $this->getMockBuilder(ReferralRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMockForAbstractClass();
        $referralRepo
            ->expects($this->once())
            ->method('save')
            ->with($expectedReferral);

        $webhookService = $this->getMockBuilder(WebhookService::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['triggerWebhooks'])
            ->getMockForAbstractClass();
        $webhookService
            ->expects($this->once())
            ->method('triggerWebhooks')
            ->with(new TriggerWebhooks(trackEvent: $expectedEvent));

        $referralService = new ReferralService(
            userService: $userService,
            referralCodeGenerator: $this->createMock(ReferralCodeGenerator::class),
            referralCodeRepository: $referralCodeRepository,
            eventService: $eventService,
            referralRepository: $referralRepo,
            webhookService: $webhookService
        );

        $actualEvent = $referralService->trackReferralEvent($trackEvent);
        $this->assertEquals($expectedEvent, $actualEvent);
    }

    public function testReferralTrackingWithInvalidCode(): void
    {
        $this->expectException(InvalidReferralCode::class);

        $referralService = new ReferralService(
            userService: $this->createMock(UserService::class),
            referralCodeGenerator: $this->createMock(ReferralCodeGenerator::class),
            referralCodeRepository: $this->createMock(ReferralCodeRepository::class),
            eventService: $this->createMock(EventService::class),
            referralRepository: $this->createMock(ReferralRepository::class),
            webhookService: $this->createMock(WebhookService::class)
        );

        $referralService->trackReferralEvent(new TrackReferralEvent(
            eventType: 'Sign Up',
            referralCode: '123',
            userIdentification: new UserIdentification(
                identificationMethod: 'email',
                identificationValue: 'john.doe@test.com'
            )
        ));
    }
}
