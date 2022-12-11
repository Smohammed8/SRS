<?php

namespace App\Controller;

use App\Entity\Slip;
use App\Entity\User;
use App\Form\SlipType;
use App\Helper\Constants;
use App\Repository\SlipRepository;
use App\Repository\PatientRepository;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Student as EntityStudentType;
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


    
    #[Route('/slip-print_pdf/{id}', name: 'print_slip', methods: ['GET'])]
    public function printSlip(DomPrint $domPrint, PaginatorInterface $paginator,Request $request,Slip $slip,CourseRepository $courseRepository) {
      {
    
        $queryBuilder =  $courseRepository->getCourses($slip->getYear(),$slip->getSemester(), $slip->getProgram());
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         20
        );


             $domPrint->print('slip/slip_pdf.html.twig', [
                 'slip' => $slip,
                 'courses' => $data,
                 'student' =>$slip->getStudent(),
                 'semester' =>$slip->getSemester(),
                 'year' => $slip->getYear(),
                 'program' => $slip->getProgram(), 
                ], "Slip",

                 
                 
             DomPrint::ORIENTATION_PORTRAIT, DomPrint::PAPER_A4, true);
           
      
            // else {

            // $this->addFlash('danger', 'Appointment closed');
            // return $this->redirect($request->headers->get('referer'));
       // }
    }
}



      #[Route('/{id}', name: 'create_slip', methods: ['POST'])]
      public function createSlip(Request $request, Slip $slip,EntityManagerInterface $entityManager): Response
      {
          $slip->setUser($this->getUser());
          $entityManager->persist($slip);
          $entityManager->flush();
          return $this->json([
              'success' => true,
              'data' => 'succedded',
          ]);
      }


    #[Route('/slip_view/{id}', name: 'slip_view', methods: ['GET'])]
    public function slipShow(EntityStudentType $entityStudentType, Request $request, PaginatorInterface $paginator,SlipRepository $slipRepository): Response
    {
        $queryBuilder =  $slipRepository->getSlips($entityStudentType,$request->query->get('search'));
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         1
      );

      
      return $this->render('slip/view_slip.html.twig', [
            'slips' => $data,
            'student' =>$entityStudentType
          
        
        ]);
    }


    #[Route('/new/{id}', name: 'slip_new', methods: ['GET', 'POST'])]
    public function new(EntityStudentType $entityStudentType,SlipRepository $slipRepository , Request $request, EntityManagerInterface $entityManager): Response
    {
        $slip = new Slip();
        $program = $entityStudentType->getProgram();
        $modality = $entityStudentType->getModality();
        $form = $this->createForm(SlipType::class, $slip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           // $acyear = $entityStudentType->getAcademicYear();
           // $adyear  = $form->get('year')->getData();
  
            $slip->setStudent($entityStudentType);
            $slip->setProgram($program);
            $slip->setModality($modality);
            $slip->setUser($this->getUser());

            $slipRepository->add($slip);
          
            $this->addFlash('success','Student\'s slip created successfully');
            return $this->redirectToRoute('slip_view',["id"=>$entityStudentType->getId()]);

    
        }

        return $this->renderForm('slip/new.html.twig', [
            'slip' => $slip,
            'form' => $form,
            'student'=>$entityStudentType
        ]);
    }

    #[Route('/{id}', name: 'slip_show', methods: ['GET'])]
    public function show(Slip $slip): Response
    {
        return $this->render('slip/show.html.twig', [
            'slip' => $slip,
        ]);
    }



    #[Route('/{id}/edit', name: 'slip_edit', methods: ['GET', 'POST'])]
    public function edit(EntityStudentType $entityPatientType, Request $request, Slip $slip, EntityManagerInterface $entityManager): Response
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
    public function delete(EntityStudentType $entityPatientType,Request $request, Slip $slip, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slip->getId(), $request->request->get('_token'))) {
            $entityManager->remove($slip);
            $entityManager->flush();
        }

        return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);

        //return $this->redirectToRoute('slip_index', [], Response::HTTP_SEE_OTHER);
    }
}
