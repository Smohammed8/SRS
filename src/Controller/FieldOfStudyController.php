<?php

namespace App\Controller;

use App\Entity\FieldOfStudy;
use App\Form\FieldOfStudyType;
use App\Repository\FieldOfStudyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/field/of/study')]
class FieldOfStudyController extends AbstractController
{
    #[Route('/', name: 'app_field_of_study_index', methods: ['GET'])]
    public function index(FieldOfStudyRepository $fieldOfStudyRepository): Response
    {
        return $this->render('field_of_study/index.html.twig', [
            'field_of_studies' => $fieldOfStudyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_field_of_study_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FieldOfStudyRepository $fieldOfStudyRepository): Response
    {
        $fieldOfStudy = new FieldOfStudy();
        $form = $this->createForm(FieldOfStudyType::class, $fieldOfStudy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fieldOfStudyRepository->add($fieldOfStudy);
            return $this->redirectToRoute('app_field_of_study_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('field_of_study/new.html.twig', [
            'field_of_study' => $fieldOfStudy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_field_of_study_show', methods: ['GET'])]
    public function show(FieldOfStudy $fieldOfStudy): Response
    {
        return $this->render('field_of_study/show.html.twig', [
            'field_of_study' => $fieldOfStudy,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_field_of_study_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FieldOfStudy $fieldOfStudy, FieldOfStudyRepository $fieldOfStudyRepository): Response
    {
        $form = $this->createForm(FieldOfStudyType::class, $fieldOfStudy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fieldOfStudyRepository->add($fieldOfStudy);
            return $this->redirectToRoute('app_field_of_study_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('field_of_study/edit.html.twig', [
            'field_of_study' => $fieldOfStudy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_field_of_study_delete', methods: ['POST'])]
    public function delete(Request $request, FieldOfStudy $fieldOfStudy, FieldOfStudyRepository $fieldOfStudyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fieldOfStudy->getId(), $request->request->get('_token'))) {
            $fieldOfStudyRepository->remove($fieldOfStudy);
        }

        return $this->redirectToRoute('app_field_of_study_index', [], Response::HTTP_SEE_OTHER);
    }
}
