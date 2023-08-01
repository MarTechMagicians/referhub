<?php

namespace App\Tests\Domain\Webhook;

use App\Domain\Event\Entity\Event;
use App\Domain\Referral\Entity\Referral;
use App\Domain\Referral\Entity\ReferralCode;
use App\Domain\User\Entity\User;
use App\Domain\Webhook\CreateWebhook;
use App\Domain\Webhook\Entity\Webhook;
use App\Domain\Webhook\TriggerWebhooks;
use App\Domain\Webhook\WebhookRepository;
use App\Domain\Webhook\WebhookService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebhookServiceTest extends TestCase
{
    public function testCreate(): void
    {
        $expectedUser = new User();
        $expectedReferralCode = (new ReferralCode())
            ->setCreatorUser($expectedUser)
            ->setCode('123');

        $expectedWebhook = (new Webhook())
            ->setUrl('google.com')
            ->setMethod('GET')
            ->setEventTypes(['Sign Up', 'Purchase'])
            ->setReferralCode($expectedReferralCode)
            ->setCreatorUser($expectedUser);

        $webhookRepo = $this->getMockBuilder(WebhookRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMockForAbstractClass();

        $webhookRepo
            ->expects($this->once())
            ->method('save')
            ->with($expectedWebhook);

        $createWebhook = new CreateWebhook(
            referralCode: $expectedReferralCode,
            url: 'google.com',
            method: 'GET',
            eventTypes: ['Sign Up', 'Purchase']
        );

        $webhookService = new WebhookService(
            webhookRepository: $webhookRepo,
            httpClient: $this->createMock(HttpClientInterface::class)
        );
        $actualWebhook = $webhookService->create($createWebhook);

        $this->assertEquals($expectedWebhook, $actualWebhook);
    }

    public function testFindByReferralCode(): void
    {
        $referralCode = new ReferralCode();

        $webhook1 = new Webhook();
        $webhook2 = new Webhook();

        $webhookRepo = $this->getMockBuilder(WebhookRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findByReferralCode'])
            ->getMockForAbstractClass();
        $webhookRepo
            ->expects($this->once())
            ->method('findByReferralCode')
            ->with($referralCode)
            ->willReturn([$webhook1, $webhook2]);

        $webhookService = new WebhookService(
            webhookRepository: $webhookRepo,
            httpClient: $this->createMock(HttpClientInterface::class)
        );
        $this->assertEquals([$webhook1, $webhook2], $webhookService->findByReferralCode($referralCode));
    }

    public function testTriggerWebhooks(): void
    {
        $referralUser = new User();
        $referralUser->setIdentificationMethod('email')
            ->setIdentificationValue('john.doe@test.com');

        $referredUser = new User();
        $referredUser
            ->setIdentificationMethod('email')
            ->setIdentificationValue('referred.user@rtest.com');

        $referralCode = new ReferralCode();
        $referralCode->setCode('123')
            ->setCreatorUser($referralUser);

        $referral = new Referral();
        $referral
            ->setCreatorUser($referralUser)
            ->setReferralCode($referralCode)
            ->setReferredUser($referredUser);

        $webhook = new Webhook();
        $webhook->setUrl('google.com')
            ->setMethod('GET')
            ->setReferralCode($referralCode)
            ->setEventTypes(['Sign Up'])
            ->setCreatorUser($referralUser);

        $webhook2 = new Webhook();
        $webhook2->setEventTypes(['Purchase', 'Subcribe']);

        $trackEvent = new Event();
        $trackEvent
            ->setCreatorUser($referralUser)
            ->setReferralCode($referralCode)
            ->setReferredUser($referredUser)
            ->setEventType('Sign Up')
            ->setEventData(['key' => 'value']);

        $webhookRepository = $this->getMockBuilder(WebhookRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['findByReferralCode'])
            ->getMockForAbstractClass();
        $webhookRepository
            ->expects($this->once())
            ->method('findByReferralCode')
            ->with($referralCode)
            ->willReturn([$webhook, $webhook2]);

        $httpClient = $this->getMockBuilder(HttpClientInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['request'])
            ->getMockForAbstractClass();
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with($webhook->getMethod(), 'google.com?referredUserIdentificationMethod=email&referredUserIdentificationValue=referred.user%40rtest.com&referralCode=123&referralUserIdentificationMethod=email&referralUserIdentificationValue=john.doe%40test.com');

        $webhookService = new WebhookService(
            webhookRepository: $webhookRepository,
            httpClient: $httpClient
        );
        $webhookService->triggerWebhooks(new TriggerWebhooks(
            trackEvent: $trackEvent
        ));
    }
}
