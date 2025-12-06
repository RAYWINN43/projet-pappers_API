<?php

namespace App\Controller;

use App\Repository\EnterpriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $req, EnterpriseRepository $repo): Response
    {
        $q = $req->query->get('q');

        return $this->render('search/results.html.twig', [
            'query' => $q,
            'results' => $repo->search($q)
        ]);
    }
}
