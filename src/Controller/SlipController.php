<?php

namespace App\Controller;

use App\Entity\Slip;
use App\Entity\User;
use App\Entity\Patient;
use App\Form\SlipType;
use App\Helper\Constants;
use App\Repository\SlipRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Patient as EntityPatientType;
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Helper\DomPrint;

#[Route('/slip')]
class SlipController extends AbstractController
{
    #[Route('/', name: 'slip_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator,SlipRepository $slipRepository): Response
    {

        $queryBuilder =  $slipRepository->getQuery($request->query->get('search'));
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         10
      );

        return $this->render('slip/index.html.twig', [
            'slips' => $data,
        ]);
    }


    #[Route('/slip_view/{id}', name: 'slip_view', methods: ['GET'])]
    public function slipShow(EntityPatientType $entityPatientType, Request $request, PaginatorInterface $paginator,SlipRepository $slipRepository): Response
    {
        $queryBuilder =  $slipRepository->getSlips($entityPatientType,$request->query->get('search'));
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         1
      );

      return $this->render('slip/view_slip.html.twig', [
            'slips' => $data,
            'patient' =>$entityPatientType
        
        ]);
    }


    #[Route('/new/{id}', name: 'slip_new', methods: ['GET', 'POST'])]
    public function new(EntityPatientType $entityPatientType,Request $request, EntityManagerInterface $entityManager): Response
    {
        $slip = new Slip();
        $patientID = $entityPatientType->getId();
   
        $form = $this->createForm(SlipType::class, $slip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           $slip->setPatient($entityPatientType);
           $slip->setSeenDate( new \DateTime());
           $slip->setStatus(Constants::OPEN);
           $slip->setApprovedBy(null);
           $slip->setUser($this->getUser());

            $entityManager->persist($slip);
            $entityManager->flush();

            return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);

          //  return $this->redirectToRoute('slip_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slip/new.html.twig', [
            'slip' => $slip,
            'form' => $form,
            'patient'=>$entityPatientType,
            'patientID'=> $patientID 
        ]);
    }

    #[Route('/{id}', name: 'slip_show', methods: ['GET'])]
    public function show(Slip $slip): Response
    {
        return $this->render('slip/show.html.twig', [
            'slip' => $slip,
        ]);
    }

    #[Route('/slip-print_pdf/{id}', name: 'print_slip', methods: ['GET'])]
    public function slipReport(DomPrint $domPrint, Request $request,Slip $slip) {
      {
        if ($slip->getStatus() === Constants::OPEN)
            
             $domPrint->print('slip/slip_pdf.html.twig', [
                 'slip' => $slip], "Slip",
                 
             DomPrint::ORIENTATION_PORTRAIT, DomPrint::PAPER_A5, true);
           
      
            else {

            $this->addFlash('danger', 'slip closed');
            return $this->redirect($request->headers->get('referer'));
        }
    }
}

    #[Route('/{id}/edit', name: 'slip_edit', methods: ['GET', 'POST'])]
    public function edit(EntityPatientType $entityPatientType, Request $request, Slip $slip, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SlipType::class, $slip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);


            //return $this->redirectToRoute('slip_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slip/edit.html.twig', [
            'slip' => $slip,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'slip_delete', methods: ['POST'])]
    public function delete(EntityPatientType $entityPatientType,Request $request, Slip $slip, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slip->getId(), $request->request->get('_token'))) {
            $entityManager->remove($slip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);

        //return $this->redirectToRoute('slip_index', [], Response::HTTP_SEE_OTHER);
    }
}
