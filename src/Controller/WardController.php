<?php

namespace App\Controller;

use App\Entity\Ward;
use App\Form\WardType;
use App\Repository\WardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
#[Route('/ward')]
class WardController extends AbstractController
{
    #[Route('/', name: 'ward_index', methods: ['GET'])]
    public function index(WardRepository $wardRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $result = $wardRepository->getWardsAsArray()->getArrayResult();
            return $this->json([
                'success' => true,
                'data' => $result
            ]);
        }
        $queryBuilder = $wardRepository->getQuery($request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('ward/index.html.twig', [
            'wards' => $data,
        ]);
    }

    #[Route('/new', name: 'ward_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ward = new Ward();
        $form = $this->createForm(WardType::class, $ward);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ward);
            $entityManager->flush();

            return $this->redirectToRoute('ward_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ward/new.html.twig', [
            'ward' => $ward,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ward_show', methods: ['GET'])]
    public function show(Ward $ward): Response
    {
        return $this->render('ward/show.html.twig', [
            'ward' => $ward,
        ]);
    }

    #[Route('/{id}/edit', name: 'ward_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ward $ward, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WardType::class, $ward);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ward_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ward/edit.html.twig', [
            'ward' => $ward,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ward_delete', methods: ['POST'])]
    public function delete(Request $request, Ward $ward, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ward->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ward);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ward_index', [], Response::HTTP_SEE_OTHER);
    }
}
