<?php

namespace App\Controller;

use App\Entity\BedTransferHistory;
use App\Form\BedTransferHistoryType;
use App\Repository\BedTransferHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/bed/transfer/history')]
class BedTransferHistoryController extends AbstractController
{
    #[Route('/', name: 'app_bed_transfer_history_index', methods: ['GET'])]
    public function index(BedTransferHistoryRepository $bedTransferHistoryRepository): Response
    {
        return $this->render('bed_transfer_history/index.html.twig', [
            'bed_transfer_histories' => $bedTransferHistoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_bed_transfer_history_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BedTransferHistoryRepository $bedTransferHistoryRepository): Response
    {
        $bedTransferHistory = new BedTransferHistory();
        $form = $this->createForm(BedTransferHistoryType::class, $bedTransferHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bedTransferHistoryRepository->add($bedTransferHistory);
            return $this->redirectToRoute('app_bed_transfer_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bed_transfer_history/new.html.twig', [
            'bed_transfer_history' => $bedTransferHistory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bed_transfer_history_show', methods: ['GET'])]
    public function show(BedTransferHistory $bedTransferHistory): Response
    {
        return $this->render('bed_transfer_history/show.html.twig', [
            'bed_transfer_history' => $bedTransferHistory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_bed_transfer_history_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BedTransferHistory $bedTransferHistory, BedTransferHistoryRepository $bedTransferHistoryRepository): Response
    {
        $form = $this->createForm(BedTransferHistoryType::class, $bedTransferHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bedTransferHistoryRepository->add($bedTransferHistory);
            return $this->redirectToRoute('app_bed_transfer_history_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bed_transfer_history/edit.html.twig', [
            'bed_transfer_history' => $bedTransferHistory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_bed_transfer_history_delete', methods: ['POST'])]
    public function delete(Request $request, BedTransferHistory $bedTransferHistory, BedTransferHistoryRepository $bedTransferHistoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bedTransferHistory->getId(), $request->request->get('_token'))) {
            $bedTransferHistoryRepository->remove($bedTransferHistory);
        }

        return $this->redirectToRoute('app_bed_transfer_history_index', [], Response::HTTP_SEE_OTHER);
    }
}
