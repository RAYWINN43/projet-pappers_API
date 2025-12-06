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

    #[ORM\Column(name: "Status", type: "string", length: 10, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(name: "JuridicalForm", type: "string", length: 50, nullable: true)]
    private ?string $juridicalForm = null;

    #[ORM\Column(name: "StartDate", type: "date", nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    private array $denominations = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnterpriseNumber(): string
    {
        return $this->enterpriseNumber;
    }

    public function setEnterpriseNumber(string $num): self
    {
        $this->enterpriseNumber = $num;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $s): self
    {
        $this->status = $s;
        return $this;
    }

    public function getJuridicalForm(): ?string
    {
        return $this->juridicalForm;
    }

    public function setJuridicalForm(?string $j): self
    {
        $this->juridicalForm = $j;
        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $d): self
    {
        $this->startDate = $d;
        return $this;
    }

    public function setDenominations(array $d): self
    {
        $this->denominations = $d;
        return $this;
    }

    public function getDenominations(): array
    {
        return $this->denominations;
    }
}
