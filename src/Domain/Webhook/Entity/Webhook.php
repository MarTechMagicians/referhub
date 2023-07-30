<?php

namespace App\Domain\Webhook\Entity;

use App\Domain\Referral\Entity\ReferralCode;
use App\Domain\User\Entity\User;
use App\Infrastructure\Persistence\Doctrine\WebhookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WebhookRepository::class)]
class Webhook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2048)]
    private ?string $url = null;

    #[ORM\Column(length: 32)]
    private ?string $method = null;

    /**
     * @var string[]
     */
    #[ORM\Column]
    private array $eventTypes = [];

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creatorUser = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ReferralCode $referralCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getCreatorUser(): ?User
    {
        return $this->creatorUser;
    }

    public function setCreatorUser(?User $creatorUser): static
    {
        $this->creatorUser = $creatorUser;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getEventTypes(): array
    {
        return $this->eventTypes;
    }

    /**
     * @param string[] $eventTypes
     */
    public function setEventTypes(array $eventTypes): self
    {
        $this->eventTypes = $eventTypes;

        return $this;
    }

    public function getReferralCode(): ?ReferralCode
    {
        return $this->referralCode;
    }

    public function setReferralCode(?ReferralCode $referralCode): self
    {
        $this->referralCode = $referralCode;

        return $this;
    }
}
