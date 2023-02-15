<?php

namespace App\Controller;

use App\Entity\AttributionPoints;
use App\Form\AttributionType;
use App\Form\AttributionByGroupeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AttributionPointsRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/admin/attribution', name: 'admin_attribution_')]
class AttributionPointsController extends AbstractController
{

    #[Route('', name: 'index')]
    public function index(AttributionPointsRepository $attribution_repository): Response
    {
        return $this->render('admin/attribution/index.html.twig', [
            'attributions' => $attribution_repository->findAll(),
        ]);
    }

    #[Route('/add-attribution', name: 'add')]
    public function addAttribution(Request $request, ManagerRegistry $doctrine): Response
    {
        $attribution = new AttributionPoints();
        $form = $this->createForm(AttributionType::class, $attribution);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $points = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($points);
            $em->flush();
            return $this->redirectToRoute('admin_attribution_index');
        }
        return $this->render('admin/attribution/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/add-attribution-groupe', name: 'add_by_groupe')]
    public function addAttributionByGroupe(Request $request, ManagerRegistry $doctrine, UserRepository $user_repository): Response
    {
        $form = $this->createForm(AttributionByGroupeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $groupe = $form->get('groupe')->getData();
            $points = $form->get('points')->getData();
            $active_at = $form->get('activeAt')->getData();
            $users = $user_repository->findBy(['groupe' => $groupe]);
            $em = $doctrine->getManager();
            if ($users) {
                foreach ($users as $user) {
                    $attribution = new AttributionPoints();
                    $attribution->setUser($user);
                    $attribution->setPoints($points);
                    $attribution->setActiveAt($active_at);
                    $em->persist($attribution);
                }
                $em->flush();
            }
            return $this->redirectToRoute('admin_attribution_index');
        }
        return $this->render('admin/attribution/add-groupe.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit-attribution/{id}', name: 'edit')]
    public function editAttribution(Request $request, AttributionPoints $attribution, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(AttributionType::class, $attribution);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $points = $form->getData();
            $em = $doctrine->getManager();
            $em->persist($points);
            $em->flush();
            return $this->redirectToRoute('admin_attribution_index');
        }
        return $this->render('admin/attribution/add.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/delete-attribution/{id}', name: 'delete')]
    public function deleteAttribution(AttributionPoints $attribution, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $em->remove($attribution);
        $em->flush();
        return $this->redirectToRoute('admin_attribution_index');
    }
}
