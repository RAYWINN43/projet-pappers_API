<?php

namespace App\Entity;

use App\Repository\DenominationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DenominationRepository::class)]
#[ORM\Table(name: "pappers_denomination")]
class Denomination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "EntityNumber", type: "string", length: 20)]
    private string $entityNumber;

    #[ORM\Column(name: "Denomination", type: "string", length: 255)]
    private string $denomination;

    #[ORM\Column(name: "TypeOfDenomination", type: "string", length: 50, nullable: true)]
    private ?string $typeOfDenomination = null;

    #[ORM\Column(name: "Language", type: "string", length: 10, nullable: true)]
    private ?string $language = null;

    public function getId(): ?int { return $this->id; }
    public function getEntityNumber(): string { return $this->entityNumber; }
    public function setEntityNumber(string $entityNumber): self { $this->entityNumber = $entityNumber; return $this; }
    public function getDenomination(): string { return $this->denomination; }
    public function setDenomination(string $denomination): self { $this->denomination = $denomination; return $this; }
    public function getTypeOfDenomination(): ?string { return $this->typeOfDenomination; }
    public function setTypeOfDenomination(?string $typeOfDenomination): self { $this->typeOfDenomination = $typeOfDenomination; return $this; }
    public function getLanguage(): ?string { return $this->language; }
    public function setLanguage(?string $language): self { $this->language = $language; return $this; }
}
