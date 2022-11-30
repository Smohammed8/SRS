<?php

namespace App\Controller;

use App\Entity\ReasonOfReferral;
use App\Form\ReasonOfReferralType;
use App\Repository\ReasonOfReferralRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reason/of/referral')]
class ReasonOfReferralController extends AbstractController
{
    #[Route('/', name: 'app_reason_of_referral_index', methods: ['GET'])]
    public function index(ReasonOfReferralRepository $reasonOfReferralRepository): Response
    {
        return $this->render('reason_of_referral/index.html.twig', [
            'reason_of_referrals' => $reasonOfReferralRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reason_of_referral_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReasonOfReferralRepository $reasonOfReferralRepository): Response
    {
        $reasonOfReferral = new ReasonOfReferral();
        $form = $this->createForm(ReasonOfReferralType::class, $reasonOfReferral);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reasonOfReferralRepository->add($reasonOfReferral);
            return $this->redirectToRoute('app_reason_of_referral_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reason_of_referral/new.html.twig', [
            'reason_of_referral' => $reasonOfReferral,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reason_of_referral_show', methods: ['GET'])]
    public function show(ReasonOfReferral $reasonOfReferral): Response
    {
        return $this->render('reason_of_referral/show.html.twig', [
            'reason_of_referral' => $reasonOfReferral,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reason_of_referral_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReasonOfReferral $reasonOfReferral, ReasonOfReferralRepository $reasonOfReferralRepository): Response
    {
        $form = $this->createForm(ReasonOfReferralType::class, $reasonOfReferral);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reasonOfReferralRepository->add($reasonOfReferral);
            return $this->redirectToRoute('app_reason_of_referral_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reason_of_referral/edit.html.twig', [
            'reason_of_referral' => $reasonOfReferral,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reason_of_referral_delete', methods: ['POST'])]
    public function delete(Request $request, ReasonOfReferral $reasonOfReferral, ReasonOfReferralRepository $reasonOfReferralRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reasonOfReferral->getId(), $request->request->get('_token'))) {
            $reasonOfReferralRepository->remove($reasonOfReferral);
        }

        return $this->redirectToRoute('app_reason_of_referral_index', [], Response::HTTP_SEE_OTHER);
    }
}
