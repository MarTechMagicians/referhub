<?php

namespace App\Domain\Referral\Entity;

use App\Domain\User\Entity\User;
use App\Infrastructure\Persistence\Doctrine\ReferralRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReferralRepository::class)]
class Referral
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ReferralCode $referralCode;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $referredUser = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creatorUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferralCode(): ReferralCode
    {
        return $this->referralCode;
    }

    public function setReferralCode(ReferralCode $referralCode): static
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

    public function setCreatorUser(?User $creatorUser): static
    {
        $this->creatorUser = $creatorUser;

        return $this;
    }
}
