<?php

namespace App\Controller;

use App\Entity\AdmimssionType;
use App\Form\AdmimssionTypeType;
use App\Repository\AdmimssionTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admimssion-type')]
class AdmimssionTypeController extends AbstractController
{
    #[Route('/', name: 'admimssion_type_index', methods: ['GET'])]
    public function index(AdmimssionTypeRepository $admimssionTypeRepository): Response
    {
        return $this->render('admimssion_type/index.html.twig', [
            'admimssion_types' => $admimssionTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admimssion_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $admimssionType = new AdmimssionType();
        $form = $this->createForm(AdmimssionTypeType::class, $admimssionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($admimssionType);
            $entityManager->flush();

            return $this->redirectToRoute('admimssion_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admimssion_type/new.html.twig', [
            'admimssion_type' => $admimssionType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admimssion_type_show', methods: ['GET'])]
    public function show(AdmimssionType $admimssionType): Response
    {
        return $this->render('admimssion_type/show.html.twig', [
            'admimssion_type' => $admimssionType,
        ]);
    }

    #[Route('/{id}/edit', name: 'admimssion_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AdmimssionType $admimssionType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdmimssionTypeType::class, $admimssionType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admimssion_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admimssion_type/edit.html.twig', [
            'admimssion_type' => $admimssionType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admimssion_type_delete', methods: ['POST'])]
    public function delete(Request $request, AdmimssionType $admimssionType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$admimssionType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($admimssionType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admimssion_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
