<?php

namespace App\Controller;

use App\Entity\BedPrice;
use App\Form\BedPriceType;
use App\Repository\BedPriceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bed-price')]
class BedPriceController extends AbstractController
{
    #[Route('/', name: 'app_bed_price_index', methods: ['GET'])]
    public function index(BedPriceRepository $bedPriceRepository): Response
    {
        return $this->render('bed_price/index.html.twig', [
            'bed_prices' => $bedPriceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bed_price_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BedPriceRepository $bedPriceRepository): Response
    {
        $bedPrice = new BedPrice();
        $form = $this->createForm(BedPriceType::class, $bedPrice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bedPriceRepository->add($bedPrice);
            return $this->redirectToRoute('app_bed_price_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bed_price/new.html.twig', [
            'bed_price' => $bedPrice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bed_price_show', methods: ['GET'])]
    public function show(BedPrice $bedPrice): Response
    {
        return $this->render('bed_price/show.html.twig', [
            'bed_price' => $bedPrice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bed_price_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BedPrice $bedPrice, BedPriceRepository $bedPriceRepository): Response
    {
        $form = $this->createForm(BedPriceType::class, $bedPrice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bedPriceRepository->add($bedPrice);
            return $this->redirectToRoute('app_bed_price_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bed_price/edit.html.twig', [
            'bed_price' => $bedPrice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bed_price_delete', methods: ['POST'])]
    public function delete(Request $request, BedPrice $bedPrice, BedPriceRepository $bedPriceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bedPrice->getId(), $request->request->get('_token'))) {
            $bedPriceRepository->remove($bedPrice);
        }

        return $this->redirectToRoute('app_bed_price_index', [], Response::HTTP_SEE_OTHER);
    }
}
