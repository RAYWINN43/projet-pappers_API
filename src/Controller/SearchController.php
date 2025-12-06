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
        $term = trim($req->query->get('q', ''));

        if ($term === '') {
            return $this->redirectToRoute('app_home');
        }

        $enterprises = $repo->search($term);

        return $this->render('search/results.html.twig', [
            'query'       => $term,
            'enterprises' => $enterprises,
        ]);
    }
}
