<?php

namespace App\Repository;

use App\Entity\Establishment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EstablishmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Establishment::class);
    }

    public function deleteOne(int $id): void
    {
        $this->createQueryBuilder('e')
            ->delete()
            ->where('e.id = :i')
            ->setParameter('i', $id)
            ->getQuery()
            ->execute();
    }

    public function deleteAllForEnterprise(string $num): void
    {
        $this->createQueryBuilder('e')
            ->delete()
            ->where('e.enterpriseNumber = :n')
            ->setParameter('n', $num)
            ->getQuery()
            ->execute();
    }
}
