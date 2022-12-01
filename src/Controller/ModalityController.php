<?php

namespace App\Controller;

use App\Entity\Modality;
use App\Form\ModalityType;
use App\Repository\ModalityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/modality')]
class ModalityController extends AbstractController
{
    #[Route('/', name: 'app_modality_index', methods: ['GET'])]
    public function index(ModalityRepository $modalityRepository): Response
    {
        return $this->render('modality/index.html.twig', [
            'modalities' => $modalityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_modality_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ModalityRepository $modalityRepository): Response
    {
        $modality = new Modality();
        $form = $this->createForm(ModalityType::class, $modality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modalityRepository->add($modality);
            return $this->redirectToRoute('app_modality_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modality/new.html.twig', [
            'modality' => $modality,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_modality_show', methods: ['GET'])]
    public function show(Modality $modality): Response
    {
        return $this->render('modality/show.html.twig', [
            'modality' => $modality,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_modality_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Modality $modality, ModalityRepository $modalityRepository): Response
    {
        $form = $this->createForm(ModalityType::class, $modality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $modalityRepository->add($modality);
            return $this->redirectToRoute('app_modality_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('modality/edit.html.twig', [
            'modality' => $modality,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_modality_delete', methods: ['POST'])]
    public function delete(Request $request, Modality $modality, ModalityRepository $modalityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modality->getId(), $request->request->get('_token'))) {
            $modalityRepository->remove($modality);
        }

        return $this->redirectToRoute('app_modality_index', [], Response::HTTP_SEE_OTHER);
    }
}
