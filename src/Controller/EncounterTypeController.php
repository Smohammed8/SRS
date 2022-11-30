<?php

namespace App\Controller;

use App\Entity\EncounterType;
use App\Form\EncounterTypeType;
use App\Repository\EncounterTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/encounter-type')]
class EncounterTypeController extends AbstractController
{
    #[Route('/', name: 'app_encounter_type_index', methods: ['GET'])]
    public function index(EncounterTypeRepository $encounterTypeRepository): Response
    {
        return $this->render('encounter_type/index.html.twig', [
            'encounter_types' => $encounterTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_encounter_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EncounterTypeRepository $encounterTypeRepository): Response
    {
        $encounterType = new EncounterType();
        $form = $this->createForm(EncounterTypeType::class, $encounterType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encounterTypeRepository->add($encounterType);
            return $this->redirectToRoute('app_encounter_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('encounter_type/new.html.twig', [
            'encounter_type' => $encounterType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_encounter_type_show', methods: ['GET'])]
    public function show(EncounterType $encounterType): Response
    {
        return $this->render('encounter_type/show.html.twig', [
            'encounter_type' => $encounterType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_encounter_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EncounterType $encounterType, EncounterTypeRepository $encounterTypeRepository): Response
    {
        $form = $this->createForm(EncounterTypeType::class, $encounterType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $encounterTypeRepository->add($encounterType);
            return $this->redirectToRoute('app_encounter_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('encounter_type/edit.html.twig', [
            'encounter_type' => $encounterType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_encounter_type_delete', methods: ['POST'])]
    public function delete(Request $request, EncounterType $encounterType, EncounterTypeRepository $encounterTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$encounterType->getId(), $request->request->get('_token'))) {
            $encounterTypeRepository->remove($encounterType);
        }

        return $this->redirectToRoute('app_encounter_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
