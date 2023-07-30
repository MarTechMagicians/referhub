<?php

namespace App\Domain\Referral;

use App\Domain\Event\CreateEvent;
use App\Domain\Event\Entity\Event;
use App\Domain\Event\EventService;
use App\Domain\Referral\Entity\Referral;
use App\Domain\Referral\Entity\ReferralCode;
use App\Domain\Referral\Exceptions\InvalidReferralCode;
use App\Domain\User\CreateUser;
use App\Domain\User\UserService;

class ReferralService
{
    public function __construct(
        private readonly UserService $userService,
        private readonly ReferralCodeGenerator $referralCodeGenerator,
        private readonly ReferralCodeRepository $referralCodeRepository,
        private readonly EventService $eventService,
        private readonly ReferralRepository $referralRepository
    ) {
    }

    public function createReferralCode(CreateReferralCode $createReferralCode): ReferralCode
    {
        // TODO create a "findOrCreate" method in user service and use it here
        $referralUser = $this->userService->create(new CreateUser(
            $createReferralCode->userIdentification
        ));

        $referralCode = new ReferralCode();
        $referralCode->setCode($this->referralCodeGenerator->generate(
            identificationMethod: $createReferralCode->userIdentification->identificationMethod,
            identificationValue: $createReferralCode->userIdentification->identificationValue
        ))
            ->setCreatorUser($referralUser);

        $this->referralCodeRepository->save($referralCode);

        return $referralCode;
    }

    public function trackReferralEvent(TrackReferralEvent $trackReferralEvent): Event
    {
        $referralCode = $this->referralCodeRepository->findOneBy(['code' => $trackReferralEvent->referralCode]);
        if (!$referralCode) {
            throw new InvalidReferralCode();
        }

        // TODO create a "findOrCreate" method in user service and use it here
        $referredUser = $this->userService->create(new CreateUser($trackReferralEvent->userIdentification));

        $referral = new Referral();
        $referral
            ->setReferralCode($referralCode)
            ->setCreatorUser($referralCode->getCreatorUser())
            ->setReferredUser($referredUser);
        $this->referralRepository->save($referral);

        return $this->eventService->createEvent(new CreateEvent(
            eventType: $trackReferralEvent->eventType,
            referralCode: $referralCode,
            userIdentification: $trackReferralEvent->userIdentification
        ));
    }
}
