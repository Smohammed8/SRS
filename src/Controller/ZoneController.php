<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Form\ZoneType;
use App\Repository\ZoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/zone')]
class ZoneController extends AbstractController
{
    #[Route('/', name: 'app_zone_index', methods: ['GET'])]
    public function index(ZoneRepository $zoneRepository): Response
    {
        return $this->render('zone/index.html.twig', [
            'zones' => $zoneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_zone_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ZoneRepository $zoneRepository): Response
    {
        $zone = new Zone();
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $zoneRepository->add($zone);
            return $this->redirectToRoute('app_zone_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('zone/new.html.twig', [
            'zone' => $zone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_zone_show', methods: ['GET'])]
    public function show(Zone $zone): Response
    {
        return $this->render('zone/show.html.twig', [
            'zone' => $zone,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_zone_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Zone $zone, ZoneRepository $zoneRepository): Response
    {
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $zoneRepository->add($zone);
            return $this->redirectToRoute('app_zone_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('zone/edit.html.twig', [
            'zone' => $zone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_zone_delete', methods: ['POST'])]
    public function delete(Request $request, Zone $zone, ZoneRepository $zoneRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$zone->getId(), $request->request->get('_token'))) {
            $zoneRepository->remove($zone);
        }

        return $this->redirectToRoute('app_zone_index', [], Response::HTTP_SEE_OTHER);
    }
}
