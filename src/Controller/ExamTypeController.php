<?php

namespace App\Controller;

use App\Entity\ExamType;
use App\Form\ExamTypeType;
use App\Repository\ExamTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/exam/type')]
class ExamTypeController extends AbstractController
{
    #[Route('/', name: 'app_exam_type_index', methods: ['GET'])]
    public function index(ExamTypeRepository $examTypeRepository): Response
    {
        return $this->render('exam_type/index.html.twig', [
            'exam_types' => $examTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_exam_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ExamTypeRepository $examTypeRepository): Response
    {
        $examType = new ExamType();
        $form = $this->createForm(ExamTypeType::class, $examType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $examTypeRepository->add($examType);
            return $this->redirectToRoute('app_exam_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exam_type/new.html.twig', [
            'exam_type' => $examType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exam_type_show', methods: ['GET'])]
    public function show(ExamType $examType): Response
    {
        return $this->render('exam_type/show.html.twig', [
            'exam_type' => $examType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_exam_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ExamType $examType, ExamTypeRepository $examTypeRepository): Response
    {
        $form = $this->createForm(ExamTypeType::class, $examType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $examTypeRepository->add($examType);
            return $this->redirectToRoute('app_exam_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('exam_type/edit.html.twig', [
            'exam_type' => $examType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exam_type_delete', methods: ['POST'])]
    public function delete(Request $request, ExamType $examType, ExamTypeRepository $examTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$examType->getId(), $request->request->get('_token'))) {
            $examTypeRepository->remove($examType);
        }

        return $this->redirectToRoute('app_exam_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
