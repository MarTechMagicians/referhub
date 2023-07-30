<?php

namespace App\Domain\Event;

use App\Domain\Event\Entity\Event;
use App\Domain\User\CreateUser;
use App\Domain\User\UserService;

class EventService
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly UserService $userService
    ) {
    }

    public function createEvent(CreateEvent $createEvent): Event
    {
        $referredUser = $this->userService->findOneBy([
            'identificationMethod' => $createEvent->userIdentification->identificationMethod,
            'identificationValue' => $createEvent->userIdentification->identificationValue,
        ]);
        if (null === $referredUser) {
            $referredUser = $this->userService->create(new CreateUser(
                $createEvent->userIdentification
            ));
        }

        $event = new Event();
        $event
            ->setCreatorUser($createEvent->referralCode->getCreatorUser())
            ->setReferralCode($createEvent->referralCode)
            ->setEventType($createEvent->eventType)
            ->setReferredUser($referredUser);

        $this->eventRepository->save($event);

        return $event;
    }
}
