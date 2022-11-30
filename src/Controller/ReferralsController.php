<?php

namespace App\Controller;

use App\Entity\Referrals;
use App\Form\ReferralsType;
use App\Repository\ReferralsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/referrals')]
class ReferralsController extends AbstractController
{
    #[Route('/', name: 'app_referrals_index', methods: ['GET'])]
    public function index(ReferralsRepository $referralsRepository): Response
    {
        return $this->render('referrals/index.html.twig', [
            'referrals' => $referralsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_referrals_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReferralsRepository $referralsRepository): Response
    {
        $referral = new Referrals();
        $form = $this->createForm(ReferralsType::class, $referral);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $referralsRepository->add($referral);
            return $this->redirectToRoute('app_referrals_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('referrals/new.html.twig', [
            'referral' => $referral,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_referrals_show', methods: ['GET'])]
    public function show(Referrals $referral): Response
    {
        return $this->render('referrals/show.html.twig', [
            'referral' => $referral,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_referrals_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Referrals $referral, ReferralsRepository $referralsRepository): Response
    {
        $form = $this->createForm(ReferralsType::class, $referral);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $referralsRepository->add($referral);
            return $this->redirectToRoute('app_referrals_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('referrals/edit.html.twig', [
            'referral' => $referral,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_referrals_delete', methods: ['POST'])]
    public function delete(Request $request, Referrals $referral, ReferralsRepository $referralsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$referral->getId(), $request->request->get('_token'))) {
            $referralsRepository->remove($referral);
        }

        return $this->redirectToRoute('app_referrals_index', [], Response::HTTP_SEE_OTHER);
    }
}
