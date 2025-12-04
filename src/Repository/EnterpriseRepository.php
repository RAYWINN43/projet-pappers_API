<?php

namespace App\Repository;

use App\Entity\Enterprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EnterpriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enterprise::class);
    }

    public function findByEnterpriseNumber(string $num): ?Enterprise
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.denominations', 'd')
            ->addSelect('d')
            ->where('e.EnterpriseNumber = :num')
            ->setParameter('num', $num)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function search(string $term): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.denominations', 'd')
            ->addSelect('d')
            ->where('e.EnterpriseNumber LIKE :term')
            ->orWhere('d.Denomination LIKE :term')
            ->setParameter('term', "%$term%")
            ->getQuery()
            ->getResult();
    }
    public function findFirst10(): array
    {
        return $this->createQueryBuilder('e')
            ->leftJoin('e.denominations', 'd')
            ->addSelect('d')
            ->groupBy('e.id')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}
