<?php

namespace App\Entity;

use App\Repository\EnterpriseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnterpriseRepository::class)]
#[ORM\Table(name: "pappers_enterprise")]
class Enterprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "EnterpriseNumber", type: "string", length: 20)]
    private string $enterpriseNumber;

    #[ORM\Column(name: "Status", type: "string", length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(name: "JuridicalForm", type: "string", length: 255, nullable: true)]
    private ?string $juridicalForm = null;

    #[ORM\Column(name: "StartDate", type: "date", nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    private array $denominations = [];
    private array $establishments = [];

    public function getId(): ?int { return $this->id; }
    public function getEnterpriseNumber(): string { return $this->enterpriseNumber; }
    public function setEnterpriseNumber(string $enterpriseNumber): self { $this->enterpriseNumber = $enterpriseNumber; return $this; }
    public function getStatus(): ?string { return $this->status; }
    public function setStatus(?string $status): self { $this->status = $status; return $this; }
    public function getJuridicalForm(): ?string { return $this->juridicalForm; }
    public function setJuridicalForm(?string $juridicalForm): self { $this->juridicalForm = $juridicalForm; return $this; }
    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; }
    public function setStartDate(?\DateTimeInterface $startDate): self { $this->startDate = $startDate; return $this; }
    public function getDenominations(): array { return $this->denominations; }
    public function setDenominations(array $denominations): self { $this->denominations = $denominations; return $this; }
    public function addDenomination(Denomination $d): self { $this->denominations[] = $d; return $this; }
    public function getEstablishments(): array { return $this->establishments; }
    public function setEstablishments(array $establishments): self { $this->establishments = $establishments; return $this; }
    public function addEstablishment(Establishment $e): self { $this->establishments[] = $e; return $this; }
}
