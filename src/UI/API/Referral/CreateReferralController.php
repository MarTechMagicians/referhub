<?php

namespace App\UI\API\Referral;

use App\Domain\Referral\Exceptions\InvalidReferralCode;
use App\Domain\Referral\ReferralService;
use App\Domain\Referral\TrackReferralEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CreateReferralController extends AbstractController
{
    public function __construct(
        private readonly ReferralService $referralService
    ) {
    }

    #[Route(path: '/api/referrals', name: 'api-create-referral', methods: 'POST')]
    public function __invoke(#[MapRequestPayload] TrackReferralEvent $trackReferralEvent): JsonResponse
    {
        try {
            $event = $this->referralService->trackReferralEvent($trackReferralEvent);
        } catch (InvalidReferralCode $e) {
            throw new BadRequestHttpException('Referral code not found', $e);
        }

        return new JsonResponse([
            'referralCode' => $event->getReferralCode()->getCode(),
        ], Response::HTTP_CREATED);
    }
}
