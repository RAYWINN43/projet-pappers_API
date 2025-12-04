<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request, Connection $connection): Response
    {
        $query = $request->query->get('q');

        if (!$query) {
            return $this->redirectToRoute('app_home');
        }

        $sql = "
           SELECT 
        pappers_enterprise.EnterpriseNumber,
        pappers_enterprise.Status,
        pappers_enterprise.JuridicalForm,
        pappers_enterprise.StartDate,
        pappers_denomination.TypeOfDenomination,
        pappers_denomination.Denomination
        FROM pappers_enterprise
    INNER JOIN pappers_denomination
        ON pappers_enterprise.EnterpriseNumber = pappers_denomination.EntityNumber
    WHERE 
        pappers_enterprise.EnterpriseNumber LIKE :q
        OR pappers_denomination.Denomination LIKE :q;
        ";

        $results = $connection->fetchAllAssociative($sql, [
            'q' => "%$query%"
        ]);

        return $this->render('search/results.html.twig', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
