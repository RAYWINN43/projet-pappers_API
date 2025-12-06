<?php

namespace App\Controller;

use App\Entity\Enterprise;
use App\Entity\Denomination;
use App\Repository\EnterpriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/enterprise')]
class EnterpriseController extends AbstractController
{
    #[Route('/enterprise/add', name: 'app_enterprise_add')]
    public function add(Request $req, EntityManagerInterface $em): Response
    {
        if ($req->isMethod('POST')) {

            $e = new Enterprise();
            $e->setEnterpriseNumber($req->request->get('EnterpriseNumber'));
            $e->setStatus($req->request->get('Status'));
            $e->setJuridicalForm($req->request->get('JuridicalForm'));

            if ($req->request->get('StartDate')) {
                $e->setStartDate(new \DateTime($req->request->get('StartDate')));
            }

            $d = new Denomination();
            $d->setDenomination($req->request->get('Denomination'));
            $d->setTypeOfDenomination($req->request->get('TypeOfDenomination'));
            $d->setLanguage($req->request->get('Language'));

            $em->persist($e);
            $em->persist($d);
            $em->flush();

            return $this->redirectToRoute('app_enterprise_view', [
                'num' => $e->getEnterpriseNumber()
            ]);
        }

        return $this->render('enterprise/add.html.twig');
    }


    #[Route('/{num}', name: 'app_enterprise_view')]
    public function view(string $num, EnterpriseRepository $repo): Response
    {
        $e = $repo->findByEnterpriseNumber($num);

        if (!$e) {
            return new Response("Entreprise introuvable", 404);
        }

        $e->setDenominations($repo->getDenominations($num));

        return $this->render('enterprise/view.html.twig', [
            'enterprise' => $e
        ]);
    }

    #[Route('/{num}/delete', name: 'app_enterprise_delete')]
    public function delete(string $num, EnterpriseRepository $repo, EntityManagerInterface $em): Response
    {
        $e = $repo->findByEnterpriseNumber($num);

        if ($e) {
            $conn = $em->getConnection();
            $conn->executeStatement("DELETE FROM pappers_denomination WHERE EntityNumber = :num", ['num' => $num]);

            $em->remove($e);
            $em->flush();
        }

        return $this->redirectToRoute('app_home');
    }
}
