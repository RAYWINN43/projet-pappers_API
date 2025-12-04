<?php

namespace App\Entity;

use App\Repository\DenominationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DenominationRepository::class)]
#[ORM\Table(name: "pappers_denomination")]
class Denomination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['search:read'])]
    private ?int $id = null;

    #[ORM\Column(name: "EntityNumber", type: "string", length: 20)]
    #[Groups(['search:read'])]
    private string $entityNumberRaw;

    #[ORM\Column(name: "TypeOfDenomination", type: "string", length: 10, nullable: true)]
    #[Groups(['search:read'])]
    private ?string $TypeOfDenomination = null;

    #[ORM\Column(name: "Denomination", type: "string", length: 255, nullable: true)]
    #[Groups(['search:read'])]
    private ?string $Denomination = null;

    #[ORM\ManyToOne(targetEntity: Enterprise::class, inversedBy: "denominations")]
    #[ORM\JoinColumn(name: "EntityNumber", referencedColumnName: "EnterpriseNumber", nullable: false)]
    private ?Enterprise $enterprise = null; // ⚠️ PAS DE Groups pour éviter les boucles

    public function getId(): ?int { return $this->id; }

    public function getEntityNumber(): string { return $this->entityNumberRaw; }
    public function setEntityNumber(string $num): self { $this->entityNumberRaw = $num; return $this; }

    public function getTypeOfDenomination(): ?string { return $this->TypeOfDenomination; }
    public function setTypeOfDenomination(?string $type): self { $this->TypeOfDenomination = $type; return $this; }

    public function getDenomination(): ?string { return $this->Denomination; }
    public function setDenomination(?string $name): self { $this->Denomination = $name; return $this; }

    public function getEnterprise(): ?Enterprise { return $this->enterprise; }
    public function setEnterprise(?Enterprise $enterprise): self { $this->enterprise = $enterprise; return $this; }
}
