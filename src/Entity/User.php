<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $identificationMethod = null;

    #[ORM\Column(length: 512)]
    private ?string $identificationValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentificationMethod(): ?string
    {
        return $this->identificationMethod;
    }

    public function setIdentificationMethod(string $identificationMethod): static
    {
        $this->identificationMethod = $identificationMethod;

        return $this;
    }

    public function getIdentificationValue(): ?string
    {
        return $this->identificationValue;
    }

    public function setIdentificationValue(string $identificationValue): static
    {
        $this->identificationValue = $identificationValue;

        return $this;
    }
}
