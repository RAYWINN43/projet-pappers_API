<?php

namespace App\Controller;

use App\Entity\Enterprise;
use App\Repository\EnterpriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/enterprise')]
class EnterpriseController extends AbstractController
{
    #[Route('/{num}', name: 'app_enterprise_view', methods: ['GET'])]
    public function view(string $num, EnterpriseRepository $repo): Response
    {
        $enterprise = $repo->findByEnterpriseNumber($num);

        if (!$enterprise) {
            throw $this->createNotFoundException("Entreprise introuvable");
        }

        return $this->render('enterprise/view.html.twig', [
            'enterprise' => $enterprise
        ]);
    }

    #[Route('/{num}/edit', name: 'app_enterprise_edit', methods: ['GET', 'POST'])]
    public function edit(string $num, Request $request, EnterpriseRepository $repo, EntityManagerInterface $em): Response
    {
        $enterprise = $repo->findByEnterpriseNumber($num);

        if (!$enterprise) {
            throw $this->createNotFoundException("Entreprise introuvable");
        }

        if ($request->isMethod('POST')) {
            $enterprise->setStatus($request->request->get('Status'));
            $enterprise->setJuridicalForm($request->request->get('JuridicalForm'));

            if ($request->request->get('StartDate')) {
                $enterprise->setStartDate(new \DateTime($request->request->get('StartDate')));
            }

            $em->flush();

            return $this->redirectToRoute('app_enterprise_view', [
                'num' => $enterprise->getEnterpriseNumber()
            ]);
        }

        return $this->render('enterprise/edit.html.twig', [
            'enterprise' => $enterprise
        ]);
    }

    #[Route('/{num}/delete', name: 'app_enterprise_delete', methods: ['GET'])]
    public function delete(string $num, EnterpriseRepository $repo, EntityManagerInterface $em): Response
    {
        $enterprise = $repo->findByEnterpriseNumber($num);

        if ($enterprise) {
            $em->remove($enterprise);
            $em->flush();
        }

        return $this->redirectToRoute('app_home');
    }
}
