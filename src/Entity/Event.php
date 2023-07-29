<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $eventType = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReferralCode $referralCode = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $referredUser = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creatorUser = null;

    /**
     * @var array<mixed>
     */
    #[ORM\Column]
    private array $eventData = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): static
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getReferralCode(): ?ReferralCode
    {
        return $this->referralCode;
    }

    public function setReferralCode(?ReferralCode $referralCode): static
    {
        $this->referralCode = $referralCode;

        return $this;
    }

    public function getReferredUser(): ?User
    {
        return $this->referredUser;
    }

    public function setReferredUser(?User $referredUser): static
    {
        $this->referredUser = $referredUser;

        return $this;
    }

    public function getCreatorUser(): ?User
    {
        return $this->creatorUser;
    }

    public function setCreatorUser(?User $creatorUser): void
    {
        $this->creatorUser = $creatorUser;
    }

    /**
     * @return array<mixed>
     */
    public function getEventData(): array
    {
        return $this->eventData;
    }

    /**
     * @param array<mixed> $eventData
     *
     * @return $this
     */
    public function setEventData(array $eventData): static
    {
        $this->eventData = $eventData;

        return $this;
    }
}
