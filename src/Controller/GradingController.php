<?php

namespace App\Controller;

use App\Entity\Grading;
use App\Form\GradingType;
use App\Repository\GradingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/grading')]
class GradingController extends AbstractController
{
    #[Route('/', name: 'app_grading_index', methods: ['GET'])]
    public function index(GradingRepository $gradingRepository): Response
    {
        return $this->render('grading/index.html.twig', [
            'gradings' => $gradingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_grading_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GradingRepository $gradingRepository): Response
    {
        $grading = new Grading();
        $form = $this->createForm(GradingType::class, $grading);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gradingRepository->add($grading);
            return $this->redirectToRoute('app_grading_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grading/new.html.twig', [
            'grading' => $grading,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grading_show', methods: ['GET'])]
    public function show(Grading $grading): Response
    {
        return $this->render('grading/show.html.twig', [
            'grading' => $grading,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_grading_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Grading $grading, GradingRepository $gradingRepository): Response
    {
        $form = $this->createForm(GradingType::class, $grading);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gradingRepository->add($grading);
            return $this->redirectToRoute('app_grading_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('grading/edit.html.twig', [
            'grading' => $grading,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_grading_delete', methods: ['POST'])]
    public function delete(Request $request, Grading $grading, GradingRepository $gradingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$grading->getId(), $request->request->get('_token'))) {
            $gradingRepository->remove($grading);
        }

        return $this->redirectToRoute('app_grading_index', [], Response::HTTP_SEE_OTHER);
    }
}
