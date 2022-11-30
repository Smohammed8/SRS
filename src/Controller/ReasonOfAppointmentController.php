<?php

namespace App\Controller;

use App\Entity\ReasonOfAppointment;
use App\Form\ReasonOfAppointmentType;
use App\Repository\ReasonOfAppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reason/of/appointment')]
class ReasonOfAppointmentController extends AbstractController
{
    #[Route('/', name: 'app_reason_of_appointment_index', methods: ['GET'])]
    public function index(ReasonOfAppointmentRepository $reasonOfAppointmentRepository): Response
    {
        return $this->render('reason_of_appointment/index.html.twig', [
            'reason_of_appointments' => $reasonOfAppointmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reason_of_appointment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReasonOfAppointmentRepository $reasonOfAppointmentRepository): Response
    {
        $reasonOfAppointment = new ReasonOfAppointment();
        $form = $this->createForm(ReasonOfAppointmentType::class, $reasonOfAppointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reasonOfAppointmentRepository->add($reasonOfAppointment);
            return $this->redirectToRoute('app_reason_of_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reason_of_appointment/new.html.twig', [
            'reason_of_appointment' => $reasonOfAppointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reason_of_appointment_show', methods: ['GET'])]
    public function show(ReasonOfAppointment $reasonOfAppointment): Response
    {
        return $this->render('reason_of_appointment/show.html.twig', [
            'reason_of_appointment' => $reasonOfAppointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reason_of_appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReasonOfAppointment $reasonOfAppointment, ReasonOfAppointmentRepository $reasonOfAppointmentRepository): Response
    {
        $form = $this->createForm(ReasonOfAppointmentType::class, $reasonOfAppointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reasonOfAppointmentRepository->add($reasonOfAppointment);
            return $this->redirectToRoute('app_reason_of_appointment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reason_of_appointment/edit.html.twig', [
            'reason_of_appointment' => $reasonOfAppointment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reason_of_appointment_delete', methods: ['POST'])]
    public function delete(Request $request, ReasonOfAppointment $reasonOfAppointment, ReasonOfAppointmentRepository $reasonOfAppointmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reasonOfAppointment->getId(), $request->request->get('_token'))) {
            $reasonOfAppointmentRepository->remove($reasonOfAppointment);
        }

        return $this->redirectToRoute('app_reason_of_appointment_index', [], Response::HTTP_SEE_OTHER);
    }
}
