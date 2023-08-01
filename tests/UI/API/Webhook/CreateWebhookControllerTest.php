<?php

namespace App\Tests\UI\API\Webhook;

use App\Domain\Referral\CreateReferralCode;
use App\Domain\Referral\ReferralService;
use App\Domain\User\UserIdentification;
use App\Tests\TransactionalWebTestCase;

class CreateWebhookControllerTest extends TransactionalWebTestCase
{
    public function testCreateWebhook(): void
    {
        /** @var ReferralService $referralService */
        $referralService = $this->getContainer()->get(ReferralService::class);

        $referralCodeEntity = $referralService->createReferralCode(new CreateReferralCode(
            new UserIdentification(
                identificationMethod: 'email',
                identificationValue: 'user@test.com'
            )
        ));

        $payload = [
            'referralCode' => $referralCodeEntity->getCode(),
            'url' => 'yahoo.com',
            'method' => 'POST',
            'eventTypes' => ['Sign Up', 'Purchase'],
        ];

        $this->client->request(
            method: 'POST',
            uri: '/api/webhooks',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('url', $responseContent);
    }

    public function testCreateWebhookWithInvalidReferralCode(): void
    {
        $payload = [
            'referralCode' => 'some referral code that does not exist',
            'url' => 'yahoo.com',
            'method' => 'POST',
            'eventTypes' => ['Sign Up', 'Purchase'],
        ];

        $this->client->request(
            method: 'POST',
            uri: '/api/webhooks',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload)
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }
}
