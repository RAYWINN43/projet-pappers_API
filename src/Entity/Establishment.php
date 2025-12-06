<?php

namespace App\Entity;

use App\Repository\EstablishmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstablishmentRepository::class)]
#[ORM\Table(name: "pappers_establishment")]
class Establishment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(name: "EnterpriseNumber", type: "string", length: 255)]
    private string $enterpriseNumber;

    #[ORM\Column(name: "EstablishmentNumber", type: "string", length: 255)]
    private string $establishmentNumber;

    #[ORM\Column(name: "StartDate", type: "date", nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    private ?string $name = null;

    public function getId(): ?int { return $this->id; }
    public function getEnterpriseNumber(): string { return $this->enterpriseNumber; }
    public function setEnterpriseNumber(string $v): self { $this->enterpriseNumber = $v; return $this; }
    public function getEstablishmentNumber(): string { return $this->establishmentNumber; }
    public function setEstablishmentNumber(string $v): self { $this->establishmentNumber = $v; return $this; }
    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; }
    public function setStartDate(?\DateTimeInterface $v): self { $this->startDate = $v; return $this; }
    public function getName(): ?string { return $this->name; }
    public function setName(?string $v): self { $this->name = $v; return $this; }
}
