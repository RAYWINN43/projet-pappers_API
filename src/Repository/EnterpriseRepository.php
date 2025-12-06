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
        return $this->findOneBy(['enterpriseNumber' => $num]);
    }

    public function findFirst(int $limit = 5): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $rows = $conn->executeQuery("
            SELECT e.*
            FROM pappers_enterprise e
            ORDER BY e.id ASC
            LIMIT $limit
        ")->fetchAllAssociative();

        $out = [];

        foreach ($rows as $r) {
            $e = new Enterprise();
            $e->setEnterpriseNumber($r["EnterpriseNumber"]);
            $e->setStatus($r["Status"]);
            $e->setJuridicalForm($r["JuridicalForm"]);
            $e->setStartDate($r["StartDate"] ? new \DateTime($r["StartDate"]) : null);
            $e->setDenominations($this->getDenominations($r["EnterpriseNumber"]));
            $out[] = $e;
        }

        return $out;
    }

    public function search(string $term): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $rows = $conn->executeQuery("
            SELECT e.*, d.Denomination
            FROM pappers_enterprise e
            LEFT JOIN pappers_denomination d ON d.EntityNumber = e.EnterpriseNumber
            WHERE e.EnterpriseNumber LIKE :t OR d.Denomination LIKE :t
            LIMIT 20
        ", ['t' => "%$term%"])->fetchAllAssociative();

        return $this->hydrate($rows);
    }

    public function getDenominations(string $num): array
    {
        return $this->getEntityManager()->getConnection()
            ->executeQuery("
                SELECT Denomination, Language, TypeOfDenomination
                FROM pappers_denomination
                WHERE EntityNumber = :n
            ", ['n' => $num])
            ->fetchAllAssociative();
    }

    private function hydrate(array $rows): array
    {
        $out = [];

        foreach ($rows as $r) {
            $num = $r["EnterpriseNumber"];

            if (!isset($out[$num])) {
                $e = new Enterprise();
                $e->setEnterpriseNumber($num);
                $e->setStatus($r["Status"]);
                $e->setJuridicalForm($r["JuridicalForm"]);
                $e->setStartDate($r["StartDate"] ? new \DateTime($r["StartDate"]) : null);
                $e->setDenominations([]);
                $out[$num] = $e;
            }

            if (!empty($r["Denomination"])) {
                $out[$num]->setDenominations(array_merge(
                    $out[$num]->getDenominations(),
                    [[ 'Denomination' => $r['Denomination'] ]]
                ));
            }
        }

        return array_values($out);
    }
}
