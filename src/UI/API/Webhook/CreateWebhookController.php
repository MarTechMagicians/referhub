<?php

namespace App\UI\API\Webhook;

use App\Domain\Referral\ReferralService;
use App\Domain\Webhook\WebhookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CreateWebhookController extends AbstractController
{
    public function __construct(
        private ReferralService $referralService,
        private WebhookService $webhookService
    ) {
    }

    #[Route(path: '/api/webhooks', name: 'api-create-webhook', methods: 'POST')]
    public function __invoke(
        #[MapRequestPayload] CreateWebhook $createWebhook
    ): JsonResponse {
        $referralCodeEntity = $this->referralService->findReferralCode($createWebhook->referralCode);
        if (null === $referralCodeEntity) {
            throw new BadRequestHttpException('Invalid referral code');
        }

        $webhook = $this->webhookService->create(new \App\Domain\Webhook\CreateWebhook(
            referralCode: $referralCodeEntity,
            url: $createWebhook->url,
            method: $createWebhook->method,
            eventTypes: $createWebhook->eventTypes
        ));

        return new JsonResponse([
            'referralCode' => $webhook->getReferralCode()->getCode(),
            'url' => $webhook->getUrl(),
            'method' => $webhook->getMethod(),
            'eventTypes' => $webhook->getEventTypes(),
        ], Response::HTTP_CREATED);
    }
}
