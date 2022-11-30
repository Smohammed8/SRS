<?php

namespace App\Controller;

use App\Entity\Woreda;
use App\Form\WoredaType;
use App\Repository\WoredaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/woreda')]
class WoredaController extends AbstractController
{
    #[Route('/', name: 'woreda_index', methods: ['GET'])]
    public function index(WoredaRepository $woredaRepository): Response
    {
        return $this->render('woreda/index.html.twig', [
            'woredas' => $woredaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'woreda_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $woreda = new Woreda();
        $form = $this->createForm(WoredaType::class, $woreda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($woreda);
            $entityManager->flush();

            return $this->redirectToRoute('woreda_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('woreda/new.html.twig', [
            'woreda' => $woreda,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'woreda_show', methods: ['GET'])]
    public function show(Woreda $woreda): Response
    {
        return $this->render('woreda/show.html.twig', [
            'woreda' => $woreda,
        ]);
    }

    #[Route('/{id}/edit', name: 'woreda_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Woreda $woreda): Response
    {
        $form = $this->createForm(WoredaType::class, $woreda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('woreda_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('woreda/edit.html.twig', [
            'woreda' => $woreda,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'woreda_delete', methods: ['POST'])]
    public function delete(Request $request, Woreda $woreda): Response
    {
        if ($this->isCsrfTokenValid('delete'.$woreda->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($woreda);
            $entityManager->flush();
        }

        return $this->redirectToRoute('woreda_index', [], Response::HTTP_SEE_OTHER);
    }
}
