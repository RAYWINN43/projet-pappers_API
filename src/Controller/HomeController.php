<?php

namespace App\Controller;

use App\Repository\EnterpriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EnterpriseRepository $repo): Response
    {
        $enterprises = $repo->findFirst10();

        return $this->render('home/index.html.twig', [
            'enterprises' => $enterprises
        ]);
    }
}
