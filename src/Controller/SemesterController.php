<?php

namespace App\Controller;

use App\Entity\Semester;
use App\Form\SemesterType;
use App\Repository\SemesterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/semester')]
class SemesterController extends AbstractController
{
    #[Route('/', name: 'app_semester_index', methods: ['GET'])]
    public function index(SemesterRepository $semesterRepository): Response
    {
        return $this->render('semester/index.html.twig', [
            'semesters' => $semesterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_semester_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SemesterRepository $semesterRepository): Response
    {
        $semester = new Semester();
        $form = $this->createForm(SemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $semesterRepository->add($semester);
            return $this->redirectToRoute('app_semester_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('semester/new.html.twig', [
            'semester' => $semester,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_semester_show', methods: ['GET'])]
    public function show(Semester $semester): Response
    {
        return $this->render('semester/show.html.twig', [
            'semester' => $semester,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_semester_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Semester $semester, SemesterRepository $semesterRepository): Response
    {
        $form = $this->createForm(SemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $semesterRepository->add($semester);
            return $this->redirectToRoute('app_semester_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('semester/edit.html.twig', [
            'semester' => $semester,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_semester_delete', methods: ['POST'])]
    public function delete(Request $request, Semester $semester, SemesterRepository $semesterRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$semester->getId(), $request->request->get('_token'))) {
            $semesterRepository->remove($semester);
        }

        return $this->redirectToRoute('app_semester_index', [], Response::HTTP_SEE_OTHER);
    }
}
