<?php

namespace App\Entity;

use App\Repository\EnterpriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: EnterpriseRepository::class)]
#[ORM\Table(name: "pappers_enterprise")]
class Enterprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    #[Groups(['search:read'])]
    private ?int $id = null;

    #[ORM\Column(name: "EnterpriseNumber", type: "string", length: 20)]
    #[Groups(['search:read'])]
    private string $EnterpriseNumber;

    #[ORM\Column(name: "Status", type: "string", length: 10, nullable: true)]
    #[Groups(['search:read'])]
    private ?string $Status = null;

    #[ORM\Column(name: "JuridicalForm", type: "string", length: 50, nullable: true)]
    #[Groups(['search:read'])]
    private ?string $JuridicalForm = null;

    #[ORM\Column(name: "StartDate", type: "date", nullable: true)]
    #[Groups(['search:read'])]
    private ?\DateTimeInterface $StartDate = null;

    #[ORM\OneToMany(mappedBy: "enterprise", targetEntity: Denomination::class)]
    #[Groups(['search:read'])]
    #[MaxDepth(1)]
    private Collection $denominations;

    public function __construct()
    {
        $this->denominations = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getEnterpriseNumber(): string { return $this->EnterpriseNumber; }
    public function setEnterpriseNumber(string $num): self { $this->EnterpriseNumber = $num; return $this; }

    public function getStatus(): ?string { return $this->Status; }
    public function setStatus(?string $status): self { $this->Status = $status; return $this; }

    public function getJuridicalForm(): ?string { return $this->JuridicalForm; }
    public function setJuridicalForm(?string $form): self { $this->JuridicalForm = $form; return $this; }

    public function getStartDate(): ?\DateTimeInterface { return $this->StartDate; }
    public function setStartDate(?\DateTimeInterface $date): self { $this->StartDate = $date; return $this; }

    public function getDenominations(): Collection { return $this->denominations; }
}
