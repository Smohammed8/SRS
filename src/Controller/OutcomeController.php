<?php

namespace App\Controller;

use App\Entity\Outcome;
use App\Form\OutcomeType;
use App\Repository\OutcomeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/outcome')]
class OutcomeController extends AbstractController
{
    #[Route('/', name: 'outcome_index', methods: ['GET'])]
    public function index(OutcomeRepository $outcomeRepository): Response
    {
        return $this->render('outcome/index.html.twig', [
            'outcomes' => $outcomeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'outcome_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $outcome = new Outcome();
        $form = $this->createForm(OutcomeType::class, $outcome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($outcome);
            $entityManager->flush();

            return $this->redirectToRoute('outcome_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('outcome/new.html.twig', [
            'outcome' => $outcome,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'outcome_show', methods: ['GET'])]
    public function show(Outcome $outcome): Response
    {
        return $this->render('outcome/show.html.twig', [
            'outcome' => $outcome,
        ]);
    }

    #[Route('/{id}/edit', name: 'outcome_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Outcome $outcome, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OutcomeType::class, $outcome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('outcome_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('outcome/edit.html.twig', [
            'outcome' => $outcome,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'outcome_delete', methods: ['POST'])]
    public function delete(Request $request, Outcome $outcome, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$outcome->getId(), $request->request->get('_token'))) {
            $entityManager->remove($outcome);
            $entityManager->flush();
        }

        return $this->redirectToRoute('outcome_index', [], Response::HTTP_SEE_OTHER);
    }
}
