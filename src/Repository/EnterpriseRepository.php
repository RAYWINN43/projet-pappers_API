<?php

namespace App\Repository;

use App\Entity\Enterprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Enterprise>
 */
class EnterpriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enterprise::class);
    }

    public function search(string $term): array
    {
        return $this->createQueryBuilder('enterprise')
            ->leftJoin('enterprise.denominations', 'denomination')
            ->addSelect('denomination')
            ->where('enterprise.EnterpriseNumber LIKE :term')
            ->orWhere('denomination.Denomination LIKE :term')
            ->setParameter('term', "%$term%")
            ->getQuery()
            ->getResult();
    }
}