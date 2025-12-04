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

    /**
     * Recherche dans les dénominations et les entreprises liées
     */
    public function search(string $term): array
    {
        return $this->createQueryBuilder('denomination')
            ->leftJoin('denomination.enterprise', 'enterprise')
            ->addSelect('enterprise')
            ->where('denomination.Denomination LIKE :term')
            ->orWhere('denomination.entityNumberRaw LIKE :term')
            ->orWhere('enterprise.EnterpriseNumber LIKE :term')
            ->setParameter('term', "%$term%")
            ->getQuery()
            ->getResult();
    }
}
