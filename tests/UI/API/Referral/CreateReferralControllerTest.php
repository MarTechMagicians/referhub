<?php

namespace App\Tests\UI\API\Referral;

use App\Domain\Referral\CreateReferralCode;
use App\Domain\Referral\ReferralService;
use App\Domain\User\Entity\User;
use App\Domain\User\UserIdentification;
use App\Tests\TransactionalWebTestCase;

class CreateReferralControllerTest extends TransactionalWebTestCase
{
    public function testCreateReferral(): void
    {
        $referralUser = new User();
        $referralUser
            ->setIdentificationMethod('email')
            ->setIdentificationValue('referral.user@test.com');

        /** @var ReferralService $referralService */
        $referralService = $this->getContainer()->get(ReferralService::class);

        $referralCodeEntity = $referralService->createReferralCode(new CreateReferralCode(new UserIdentification(
            identificationMethod: $referralUser->getIdentificationMethod(),
            identificationValue: $referralUser->getIdentificationValue()
        )));

        $payload = [
            'eventType' => 'Sign Up',
            'referralCode' => $referralCodeEntity->getCode(),
            'userIdentification' => [
                'identificationMethod' => 'email',
                'identificationValue' => 'referred.user@test.com',
            ],
        ];

        $this->client->request(
            method: 'POST',
            uri: '/api/referrals',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload)
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('referralCode', $responseContent);
    }

    public function testCreateReferralForInvalidCode(): void
    {
        $payload = [
            'eventType' => 'Sign Up',
            'referralCode' => 'some code that does not exist',
            'userIdentification' => [
                'identificationMethod' => 'email',
                'identificationValue' => 'referred.user@test.com',
            ],
        ];

        $this->client->request(
            method: 'POST',
            uri: '/api/referrals',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($payload)
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }
}
