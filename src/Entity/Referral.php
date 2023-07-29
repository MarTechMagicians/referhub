<?php

namespace App\Entity;

use App\Repository\ReferralRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReferralRepository::class)]
class Referral
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: ReferralCode::class)]
    private Collection $referralCode;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $referredUser = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creatorUser = null;

    public function __construct()
    {
        $this->referralCode = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ReferralCode>
     */
    public function getReferralCode(): Collection
    {
        return $this->referralCode;
    }

    public function addReferralCode(ReferralCode $referralCode): static
    {
        if (!$this->referralCode->contains($referralCode)) {
            $this->referralCode->add($referralCode);
        }

        return $this;
    }

    public function removeReferralCode(ReferralCode $referralCode): static
    {
        $this->referralCode->removeElement($referralCode);

        return $this;
    }

    public function getReferredUser(): ?User
    {
        return $this->referredUser;
    }

    public function setReferredUser(?User $referredUser): void
    {
        $this->referredUser = $referredUser;
    }

    public function getCreatorUser(): ?User
    {
        return $this->creatorUser;
    }

    public function setCreatorUser(?User $creatorUser): void
    {
        $this->creatorUser = $creatorUser;
    }
}
