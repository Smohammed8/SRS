<?php

namespace App\Controller;

use App\Entity\ProgramLevel;
use App\Form\ProgramLevelType;
use App\Repository\ProgramLevelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/program-level')]
class ProgramLevelController extends AbstractController
{
    #[Route('/', name: 'app_program_level_index', methods: ['GET'])]
    public function index(ProgramLevelRepository $programLevelRepository): Response
    {
        return $this->render('program_level/index.html.twig', [
            'program_levels' => $programLevelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_program_level_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProgramLevelRepository $programLevelRepository): Response
    {
        $programLevel = new ProgramLevel();
        $form = $this->createForm(ProgramLevelType::class, $programLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programLevelRepository->add($programLevel);
            return $this->redirectToRoute('app_program_level_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program_level/new.html.twig', [
            'program_level' => $programLevel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_program_level_show', methods: ['GET'])]
    public function show(ProgramLevel $programLevel): Response
    {
        return $this->render('program_level/show.html.twig', [
            'program_level' => $programLevel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_program_level_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProgramLevel $programLevel, ProgramLevelRepository $programLevelRepository): Response
    {
        $form = $this->createForm(ProgramLevelType::class, $programLevel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programLevelRepository->add($programLevel);
            return $this->redirectToRoute('app_program_level_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program_level/edit.html.twig', [
            'program_level' => $programLevel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_program_level_delete', methods: ['POST'])]
    public function delete(Request $request, ProgramLevel $programLevel, ProgramLevelRepository $programLevelRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$programLevel->getId(), $request->request->get('_token'))) {
            $programLevelRepository->remove($programLevel);
        }

        return $this->redirectToRoute('app_program_level_index', [], Response::HTTP_SEE_OTHER);
    }
}
