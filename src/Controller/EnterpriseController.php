<?php

namespace App\Controller;

use App\Entity\Enterprise;
use App\Repository\EnterpriseRepository;
use App\Repository\DenominationRepository;
use App\Repository\EstablishmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Denomination;

class EnterpriseController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(EnterpriseRepository $repo): Response
    {
        return $this->render('home/index.html.twig', [
            'enterprises' => $repo->findFirst(5)
        ]);
    }

    #[Route('/search', name: 'enterprise_search')]
    public function search(Request $r, EnterpriseRepository $repo): Response
    {
        $q = (string)$r->query->get('q', '');
        $results = $q ? $repo->search($q) : [];
        return $this->render('search/results.html.twig', [
            'query' => $q,
            'results' => $results
        ]);
    }

    #[Route('/enterprise/add', name: 'enterprise_add')]
    public function add(Request $r, EntityManagerInterface $em, DenominationRepository $dr): Response
    {
        if ($r->isMethod('POST')) {
            $num = $r->request->get('enterpriseNumber');
            $e = new Enterprise();
            $e->setEnterpriseNumber($num);
            $e->setStatus($r->request->get('status'));
            $e->setJuridicalForm($r->request->get('juridicalForm'));
            $sd = $r->request->get('startDate');
            if ($sd) $e->setStartDate(new \DateTime($sd));
            $em->persist($e);
            $em->flush();

            $d = new Denomination();
            $d->setEntityNumber($num);
            $d->setDenomination($r->request->get('denomination'));
            $d->setTypeOfDenomination($r->request->get('denominationType'));
            $d->setLanguage($r->request->get('denominationLanguage'));
            $em->persist($d);
            $em->flush();

            return $this->redirectToRoute('enterprise_view', ['num' => $num]);
        }

        return $this->render('enterprise/add.html.twig');
    }


    #[Route('/enterprise/{num}', name: 'enterprise_view')]
    public function view(string $num, EnterpriseRepository $repo): Response
    {
        $e = $repo->findOneWithDetails($num);
        if (!$e) throw $this->createNotFoundException();
        return $this->render('enterprise/view.html.twig', [
            'enterprise' => $e
        ]);
    }

   #[Route('/enterprise/{num}/edit', name: 'enterprise_edit')]
    public function edit(
        string $num,
        Request $r,
        EnterpriseRepository $repo,
        DenominationRepository $dr,
        EntityManagerInterface $em
    ): Response {
        $e = $repo->findOneWithDetails($num);
        if (!$e) throw $this->createNotFoundException();

        if ($r->isMethod('POST')) {
            $m = $em->getRepository(Enterprise::class)->findOneBy(['enterpriseNumber' => $num]);
            if (!$m) { $m = new Enterprise(); $m->setEnterpriseNumber($num); }

            $m->setStatus($r->request->get('status'));
            $m->setJuridicalForm($r->request->get('juridicalForm'));
            $sd = $r->request->get('startDate');
            if ($sd) $m->setStartDate(new \DateTime($sd));
            $em->persist($m);
            $em->flush();

            $d = $dr->findOneBy(['entityNumber' => $num]);
            if (!$d) { $d = new Denomination(); $d->setEntityNumber($num); }

            $d->setDenomination($r->request->get('denomination'));
            $d->setTypeOfDenomination($r->request->get('denominationType'));
            $d->setLanguage($r->request->get('denominationLanguage'));
            $em->persist($d);
            $em->flush();

            return $this->redirectToRoute('enterprise_view', ['num' => $num]);
        }

        $mainDenom = $e->getDenominations()[0] ?? null;

        return $this->render('enterprise/edit.html.twig', [
            'enterprise' => $e,
            'mainDenom' => $mainDenom
        ]);
    }


    #[Route('/enterprise/{num}/delete', name: 'enterprise_delete', methods: ['POST'])]
    public function delete(string $num, DenominationRepository $dr, EstablishmentRepository $er, EntityManagerInterface $em): Response
    {
        $dr->deleteAllForEnterprise($num);
        $er->deleteAllForEnterprise($num);
        $m = $em->getRepository(Enterprise::class)->findOneBy(['enterpriseNumber' => $num]);
        if ($m) $em->remove($m);
        $em->flush();
        return $this->redirectToRoute('home');
    }

    #[Route('/establishment/{id}/delete', name: 'establishment_delete', methods: ['POST'])]
    public function delEst(int $id, EstablishmentRepository $repo): Response
    {
        $repo->deleteOne($id);
        return $this->redirectToRoute('home');
    }
}
