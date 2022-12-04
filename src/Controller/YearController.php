<?php

namespace App\Controller;

use App\Entity\Year;
use App\Form\YearType;
use App\Repository\YearRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/year')]
class YearController extends AbstractController
{
    #[Route('/', name: 'app_year_index', methods: ['GET'])]
    public function index(YearRepository $yearRepository): Response
    {
        return $this->render('year/index.html.twig', [
            'years' => $yearRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_year_new', methods: ['GET', 'POST'])]
    public function new(Request $request, YearRepository $yearRepository): Response
    {
        $year = new Year();
        $form = $this->createForm(YearType::class, $year);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $yearRepository->add($year);
            return $this->redirectToRoute('app_year_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('year/new.html.twig', [
            'year' => $year,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_year_show', methods: ['GET'])]
    public function show(Year $year): Response
    {
        return $this->render('year/show.html.twig', [
            'year' => $year,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_year_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Year $year, YearRepository $yearRepository): Response
    {
        $form = $this->createForm(YearType::class, $year);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $yearRepository->add($year);
            return $this->redirectToRoute('app_year_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('year/edit.html.twig', [
            'year' => $year,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_year_delete', methods: ['POST'])]
    public function delete(Request $request, Year $year, YearRepository $yearRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$year->getId(), $request->request->get('_token'))) {
            $yearRepository->remove($year);
        }

        return $this->redirectToRoute('app_year_index', [], Response::HTTP_SEE_OTHER);
    }
}
