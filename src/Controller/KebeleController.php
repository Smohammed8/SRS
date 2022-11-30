<?php

namespace App\Controller;

use App\Entity\Kebele;
use App\Form\KebeleType;
use App\Repository\KebeleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/kebele')]
class KebeleController extends AbstractController
{
    #[Route('/', name: 'kebele_index', methods: ['GET'])]
    public function index(KebeleRepository $kebeleRepository): Response
    {
        return $this->render('kebele/index.html.twig', [
            'kebeles' => $kebeleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'kebele_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $kebele = new Kebele();
        $form = $this->createForm(KebeleType::class, $kebele);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($kebele);
            $entityManager->flush();

            return $this->redirectToRoute('kebele_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('kebele/new.html.twig', [
            'kebele' => $kebele,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'kebele_show', methods: ['GET'])]
    public function show(Kebele $kebele): Response
    {
        return $this->render('kebele/show.html.twig', [
            'kebele' => $kebele,
        ]);
    }

    #[Route('/{id}/edit', name: 'kebele_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Kebele $kebele): Response
    {
        $form = $this->createForm(KebeleType::class, $kebele);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('kebele_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('kebele/edit.html.twig', [
            'kebele' => $kebele,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'kebele_delete', methods: ['POST'])]
    public function delete(Request $request, Kebele $kebele): Response
    {
        if ($this->isCsrfTokenValid('delete'.$kebele->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($kebele);
            $entityManager->flush();
        }

        return $this->redirectToRoute('kebele_index', [], Response::HTTP_SEE_OTHER);
    }
}
