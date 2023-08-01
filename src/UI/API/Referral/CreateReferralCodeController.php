<?php

namespace App\UI\API\Referral;

use App\Domain\Referral\CreateReferralCode;
use App\Domain\Referral\ReferralService;
use App\Domain\User\UserIdentification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class CreateReferralCodeController extends AbstractController
{
    public function __construct(
        private readonly ReferralService $referralService
    ) {
    }

    #[Route(path: '/api/referral-codes', name: 'api-create-referral-code', methods: 'POST')]
    public function __invoke(
        #[MapRequestPayload] UserIdentification $userIdentification
    ): JsonResponse {
        $referralCode = $this->referralService->createReferralCode(new CreateReferralCode($userIdentification));

        return $this->json([
            'referralCode' => $referralCode->getCode(),
        ], Response::HTTP_CREATED);
    }
}
