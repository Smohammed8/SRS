<?php

namespace App\Controller;

use App\Entity\VitalSign;
use App\Entity\Admimssion;
use App\Form\VitalSignType;
use App\Repository\VitalSignRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Patient as EntityPatientType;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/vital/sign')]
class VitalSignController extends AbstractController
{


    #[Route('/', name: 'app_vital_sign_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator,Request $request,VitalSignRepository $vitalSignRepository): Response
    {

        $queryBuilder = $vitalSignRepository->getQuery();
           $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('vital_sign/index.html.twig', [
            'vital_signs' => $data,

         
        ]);
    }

    #[Route('/{id}', name: 'vital_sign', methods: ['GET'])]
    public function view_vital_sign(PaginatorInterface $paginator, Request $request,EntityPatientType $entityPatientType, VitalSignRepository $vitalSignRepository): Response
    {

        $queryBuilder = $vitalSignRepository->getQuery();
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('vital_sign/index.html.twig', [
            'vital_signs' => $data,

            'patient' =>$entityPatientType
        ]);
    }

    #[Route('/new', name: 'app_vital_sign_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VitalSignRepository $vitalSignRepository): Response
    {
        $vitalSign = new VitalSign();
        $form = $this->createForm(VitalSignType::class, $vitalSign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vitalSignRepository->add($vitalSign);
            return $this->redirectToRoute('app_vital_sign_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vital_sign/new.html.twig', [
            'vital_sign' => $vitalSign,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vital_sign_show', methods: ['GET'])]
    public function show(VitalSign $vitalSign): Response
    {
        return $this->render('vital_sign/show.html.twig', [
            'vital_sign' => $vitalSign,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_vital_sign_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VitalSign $vitalSign, VitalSignRepository $vitalSignRepository): Response
    {
        $form = $this->createForm(VitalSignType::class, $vitalSign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vitalSignRepository->add($vitalSign);
            return $this->redirectToRoute('app_vital_sign_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vital_sign/edit.html.twig', [
            'vital_sign' => $vitalSign,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_vital_sign_delete', methods: ['POST'])]
    public function delete(Request $request, VitalSign $vitalSign, VitalSignRepository $vitalSignRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vitalSign->getId(), $request->request->get('_token'))) {
            $vitalSignRepository->remove($vitalSign);
        }

        return $this->redirectToRoute('app_vital_sign_index', [], Response::HTTP_SEE_OTHER);
    }
}
