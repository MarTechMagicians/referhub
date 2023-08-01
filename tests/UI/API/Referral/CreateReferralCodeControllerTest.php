<?php

namespace App\Tests\UI\API\Referral;

use App\Tests\TransactionalWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateReferralCodeControllerTest extends TransactionalWebTestCase
{
    public function testGenerateReferralCode(): void
    {
        $data = [
            'identificationMethod' => 'email',
            'identificationValue' => 'user@example.com',
        ];

        // Make the API request
        $this->client->request(
            method: 'POST',
            uri: '/api/referral-codes',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($data)
        );

        // Assert the response status code
        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        // Assert the response content (adjust the structure as needed)
        $responseContent = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('referralCode', $responseContent);
    }

    public function testWithMissingIdentificationMethod(): void
    {
        $data = [
            'identificationValue' => 'user@example.com',
        ];

        $this->client->request(
            method: 'POST',
            uri: '/api/referral-codes',
            server: ['CONTENT_TYPE' => 'application/json'],
            content: json_encode($data)
        );

        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $this->client->getResponse()->getStatusCode());
    }
}
