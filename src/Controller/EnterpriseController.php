<?php

namespace App\Controller;

use App\Entity\Enterprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/enterprise')]
class EnterpriseController extends AbstractController
{
    #[Route('/{id}', name: 'app_enterprise_view', methods: ['GET'])]
    public function view(Enterprise $enterprise): Response
    {
        
    }

    #[Route('/add', name: 'app_enterprise_add', methods: ['GET', 'POST'])]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        
    }

    #[Route('/{id}/edit', name: 'app_enterprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Enterprise $enterprise, EntityManagerInterface $em): Response
    {
        
    }

    #[Route('/{id}/delete', name: 'app_enterprise_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $em, Enterprise $enterprise): Response
    {
        $em->remove($enterprise);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
}
