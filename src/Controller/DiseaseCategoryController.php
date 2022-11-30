<?php

namespace App\Controller;

use App\Entity\DiseaseCategory;
use App\Form\DiseaseCategoryType;
use App\Repository\DiseaseCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/disease/category')]
class DiseaseCategoryController extends AbstractController
{
    #[Route('/', name: 'app_disease_category_index', methods: ['GET'])]
    public function index(DiseaseCategoryRepository $diseaseCategoryRepository): Response
    {
        return $this->render('disease_category/index.html.twig', [
            'disease_categories' => $diseaseCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_disease_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DiseaseCategoryRepository $diseaseCategoryRepository): Response
    {
        $diseaseCategory = new DiseaseCategory();
        $form = $this->createForm(DiseaseCategoryType::class, $diseaseCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diseaseCategoryRepository->add($diseaseCategory);
            return $this->redirectToRoute('app_disease_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('disease_category/new.html.twig', [
            'disease_category' => $diseaseCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_disease_category_show', methods: ['GET'])]
    public function show(DiseaseCategory $diseaseCategory): Response
    {
        return $this->render('disease_category/show.html.twig', [
            'disease_category' => $diseaseCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_disease_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DiseaseCategory $diseaseCategory, DiseaseCategoryRepository $diseaseCategoryRepository): Response
    {
        $form = $this->createForm(DiseaseCategoryType::class, $diseaseCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diseaseCategoryRepository->add($diseaseCategory);
            return $this->redirectToRoute('app_disease_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('disease_category/edit.html.twig', [
            'disease_category' => $diseaseCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_disease_category_delete', methods: ['POST'])]
    public function delete(Request $request, DiseaseCategory $diseaseCategory, DiseaseCategoryRepository $diseaseCategoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diseaseCategory->getId(), $request->request->get('_token'))) {
            $diseaseCategoryRepository->remove($diseaseCategory);
        }

        return $this->redirectToRoute('app_disease_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
