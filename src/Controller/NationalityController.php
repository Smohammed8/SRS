<?php

namespace App\Controller;

use App\Entity\Nationality;
use App\Form\NationalityType;
use App\Repository\NationalityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/nationality')]
class NationalityController extends AbstractController
{
    #[Route('/', name: 'app_nationality_index', methods: ['GET'])]
    public function index(NationalityRepository $nationalityRepository): Response
    {
        return $this->render('nationality/index.html.twig', [
            'nationalities' => $nationalityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_nationality_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NationalityRepository $nationalityRepository): Response
    {
        $nationality = new Nationality();
        $form = $this->createForm(NationalityType::class, $nationality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nationalityRepository->add($nationality);
            return $this->redirectToRoute('app_nationality_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nationality/new.html.twig', [
            'nationality' => $nationality,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nationality_show', methods: ['GET'])]
    public function show(Nationality $nationality): Response
    {
        return $this->render('nationality/show.html.twig', [
            'nationality' => $nationality,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_nationality_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Nationality $nationality, NationalityRepository $nationalityRepository): Response
    {
        $form = $this->createForm(NationalityType::class, $nationality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $nationalityRepository->add($nationality);
            return $this->redirectToRoute('app_nationality_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('nationality/edit.html.twig', [
            'nationality' => $nationality,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_nationality_delete', methods: ['POST'])]
    public function delete(Request $request, Nationality $nationality, NationalityRepository $nationalityRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$nationality->getId(), $request->request->get('_token'))) {
            $nationalityRepository->remove($nationality);
        }

        return $this->redirectToRoute('app_nationality_index', [], Response::HTTP_SEE_OTHER);
    }
}
