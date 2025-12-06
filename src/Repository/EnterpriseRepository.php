<?php

namespace App\Repository;

use App\Entity\Denomination;
use App\Entity\Enterprise;
use App\Entity\Establishment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

class EnterpriseRepository extends ServiceEntityRepository
{
    private Connection $connection;

    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        parent::__construct($registry, Enterprise::class);
        $this->connection = $connection;
    }

    private function hydrateEnterprise(array $row): Enterprise
    {
        $e = new Enterprise();
        $e->setEnterpriseNumber($row['EnterpriseNumber']);
        $e->setStatus($row['Status'] ?? null);
        $e->setJuridicalForm($row['JuridicalForm'] ?? null);
        if (!empty($row['StartDate'])) { $e->setStartDate(new \DateTime($row['StartDate'])); }
        return $e;
    }

    public function findFirst(int $limit = 5): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('e.*')->from('pappers_enterprise', 'e')->orderBy('e.EnterpriseNumber')->setMaxResults($limit);
        $rows = $qb->executeQuery()->fetchAllAssociative();

        $list = [];
        foreach ($rows as $row) {
            $e = $this->hydrateEnterprise($row);
            $e->setDenominations($this->getDenominations($e->getEnterpriseNumber(), 1));
            $list[] = $e;
        }
        return $list;
    }

    public function findOneWithDetails(string $num): ?Enterprise
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('e.*')->from('pappers_enterprise', 'e')->where('e.EnterpriseNumber = :n')->setParameter('n', $num);
        $row = $qb->executeQuery()->fetchAssociative();
        if (!$row) return null;

        $e = $this->hydrateEnterprise($row);
        $e->setDenominations($this->getDenominations($num));
        $e->setEstablishments($this->getEstablishments($num));
        return $e;
    }

    public function search(string $term, int $limit = 50): array
    {
        $like = '%' . $term . '%';
        $qb = $this->connection->createQueryBuilder();
        $qb->select('DISTINCT e.*')
            ->from('pappers_enterprise', 'e')
            ->leftJoin('e', 'pappers_denomination', 'd', 'd.EntityNumber = e.EnterpriseNumber')
            ->where('e.EnterpriseNumber LIKE :t OR d.Denomination LIKE :t')
            ->setParameter('t', $like)
            ->setMaxResults($limit);

        $rows = $qb->executeQuery()->fetchAllAssociative();

        $list = [];
        foreach ($rows as $row) {
            $e = $this->hydrateEnterprise($row);
            $e->setDenominations($this->getDenominations($e->getEnterpriseNumber(), 1));
            $list[] = $e;
        }
        return $list;
    }

    public function getDenominations(string $num, int $limit = 0): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('d.*')->from('pappers_denomination', 'd')->where('d.EntityNumber = :n')->setParameter('n', $num)->orderBy('d.id');
        if ($limit > 0) $qb->setMaxResults($limit);

        $rows = $qb->executeQuery()->fetchAllAssociative();

        $list = [];
        foreach ($rows as $r) {
            $d = new Denomination();
            $d->setEntityNumber($r['EntityNumber']);
            $d->setDenomination($r['Denomination']);
            $d->setTypeOfDenomination($r['TypeOfDenomination']);
            $d->setLanguage($r['Language']);
            $list[] = $d;
        }
        return $list;
    }

    public function getEstablishments(string $num): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('e.*', 'd.Denomination AS name')
            ->from('pappers_establishment', 'e')
            ->leftJoin('e', 'pappers_denomination', 'd', 'd.EntityNumber = e.EstablishmentNumber')
            ->where('e.EnterpriseNumber = :n')
            ->setParameter('n', $num)
            ->orderBy('e.EstablishmentNumber');

        $rows = $qb->executeQuery()->fetchAllAssociative();

        $list = [];
        foreach ($rows as $r) {
            $est = new Establishment();
            $est->setEnterpriseNumber($r['EnterpriseNumber']);
            $est->setEstablishmentNumber($r['EstablishmentNumber']);
            if (!empty($r['StartDate'])) { $est->setStartDate(new \DateTime($r['StartDate'])); }
            $est->setName($r['name']);
            $list[] = $est;
        }

        return $list;
    }
}