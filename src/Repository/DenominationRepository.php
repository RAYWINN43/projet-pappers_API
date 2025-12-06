<?php

namespace App\Repository;

use App\Entity\Denomination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DenominationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Denomination::class);
    }

    public function deleteAllForEnterprise(string $num): void
    {
        $this->createQueryBuilder('d')
            ->delete()
            ->where('d.entityNumber = :n')
            ->setParameter('n', $num)
            ->getQuery()
            ->execute();
    }
}
