<?php

namespace App\Controller;

use App\Repository\EnterpriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search', methods: ['GET'])]
    public function search(Request $request, EnterpriseRepository $enterpriseRepository): Response
    {
        $query = $request->query->get('q', '');

        $results = $enterpriseRepository->search($query);

        return $this->render('search/results.html.twig', [
            'query' => $query,
            'results' => $results
        ]);
    }
}
