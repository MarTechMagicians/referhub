<?php

namespace App\Domain\Webhook;

use App\Domain\Event\Entity\Event;
use App\Domain\Referral\Entity\ReferralCode;
use App\Domain\Referral\Exceptions\InvalidReferralCode;
use App\Domain\Referral\ReferralService;
use App\Domain\Webhook\Entity\Webhook;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebhookService
{
    public function __construct(
        private readonly WebhookRepository $webhookRepository,
        private readonly ReferralService $referralService,
        private readonly HttpClientInterface $httpClient
    ) {
    }

    public function create(CreateWebhook $createWebhook): Webhook
    {
        $referralCode = $this->referralService->findReferralCode($createWebhook->referralCode);
        if (null === $referralCode) {
            throw new InvalidReferralCode();
        }

        $webhook = new Webhook();
        $webhook
            ->setCreatorUser($referralCode->getCreatorUser())
            ->setMethod($createWebhook->method)
            ->setUrl($createWebhook->url)
            ->setEventTypes($createWebhook->eventTypes)
            ->setReferralCode($referralCode);

        $this->webhookRepository->save($webhook);

        return $webhook;
    }

    /**
     * @return Webhook[]
     */
    public function findByReferralCode(ReferralCode $referralCode): array
    {
        return $this->webhookRepository->findByReferralCode($referralCode);
    }

    public function triggerWebhooks(TriggerWebhooks $triggerWebhooks): void
    {
        $webhooks = $this->findByReferralCode($triggerWebhooks->trackEvent->getReferralCode());
        $webhooks = array_filter($webhooks, fn (Webhook $webhook) => \in_array($triggerWebhooks->trackEvent->getEventType(), $webhook->getEventTypes()));

        /** @var Webhook $webhook */
        foreach ($webhooks as $webhook) {
            $options = [];
            $url = $webhook->getUrl();

            if (Request::METHOD_GET === $webhook->getMethod()) {
                $url = $url.'?'.http_build_query($this->preparePayload($triggerWebhooks->trackEvent));
            }

            if (Request::METHOD_POST === $webhook->getMethod()) {
                $options['body'] = $this->preparePayload($triggerWebhooks->trackEvent);
            }

            $this->httpClient->request(
                method: $webhook->getMethod(),
                url: $url,
                options: $options
            );
        }
    }

    /**
     * @return array<string, string>
     */
    private function preparePayload(Event $event): array
    {
        $payload = [];
        $payload['referredUserIdentificationMethod'] = $event->getReferredUser()->getIdentificationMethod();
        $payload['referredUserIdentificationValue'] = $event->getReferredUser()->getIdentificationValue();
        $payload['referralCode'] = $event->getReferralCode()->getCode();
        $payload['referralUserIdentificationMethod'] = $event->getCreatorUser()->getIdentificationMethod();
        $payload['referralUserIdentificationValue'] = $event->getCreatorUser()->getIdentificationValue();

        return $payload;
    }
}
