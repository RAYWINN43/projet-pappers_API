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

    #[ORM\Column(name: "EntityNumber", type: "string", length: 50)]
    private string $EntityNumber;

    #[ORM\Column(name: "Language", type: "string", length: 5, nullable: true)]
    private ?string $Language = null;

    #[ORM\Column(name: "TypeOfDenomination", type: "string", length: 10, nullable: true)]
    private ?string $TypeOfDenomination = null;

    #[ORM\Column(name: "Denomination", type: "string", length: 255, nullable: true)]
    private ?string $Denomination = null;

    public function getId(): ?int { return $this->id; }

    public function getEntityNumber(): string { return $this->EntityNumber; }
    public function setEntityNumber(string $num): self { $this->EntityNumber = $num; return $this; }

    public function getLanguage(): ?string { return $this->Language; }
    public function setLanguage(?string $l): self { $this->Language = $l; return $this; }

    public function getTypeOfDenomination(): ?string { return $this->TypeOfDenomination; }
    public function setTypeOfDenomination(?string $t): self { $this->TypeOfDenomination = $t; return $this; }

    public function getDenomination(): ?string { return $this->Denomination; }
    public function setDenomination(?string $d): self { $this->Denomination = $d; return $this; }
}
