<?php

namespace App\Controller;

use App\Entity\Assessment;
use App\Form\AssessmentType;
use App\Repository\AssessmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use App\Helper\Constants;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Helper\DomPrint;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Patient as EntityPatientType;
use DateTime;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/assessment')]
class AssessmentController extends AbstractController
{
    #[Route('/{id}', name: 'app_assessment_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator,EntityPatientType $entityPatientType,AssessmentRepository $assessmentRepository,Request $request): Response
    {

        $queryBuilder =  $assessmentRepository->getAssessments($entityPatientType, $request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            1
        );
        return $this->render('assessment/index.html.twig', [
            'assessments' =>$data,
            'patient' =>$entityPatientType,
        ]);
    }

    #[Route('/new/{id}', name: 'app_assessment_new', methods: ['GET', 'POST'])]
    public function new(EntityPatientType $entityPatientType, Request $request, AssessmentRepository $assessmentRepository): Response
    {
        $assessment = new Assessment();
        $form = $this->createForm(AssessmentType::class, $assessment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assessment->setPatient($entityPatientType);
            $assessment->setCreatedAt(new DateTime());
            $assessment->setPhyscian($this->getUser());
              ////////////////////////////////////////////////

            
              $note = $form['note']->getData();       
              if($note == null ){

                $this->addFlash('danger','No any assessment is added!'); 
                return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);

                }
            $uploadedFile = $form['file']->getData();

            if( $uploadedFile != null){
        
            $destination = $this->getParameter('kernel.project_dir') . '/public/assessments';
            $newFilename = $assessment->getId() . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
          
           
            try{
              $uploadedFile->move($destination, $newFilename);

            } catch(FileException $e) {
                $this->addFlash('danger','No directoty found to store your uplods!'); 

             }
              $assessment->setFile($newFilename);
            } 
            else{

             $assessment->setFile(null);
            }

            

           /////////////////////////////////////////////////////////////////////
            $assessmentRepository->add($assessment);
            $this->addFlash('success','Assessment added successfully!');
         
            return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);
        
        }
        return $this->renderForm('assessment/new.html.twig', [
            'assessment' => $assessment,
            'patient' =>$entityPatientType,
            'form' => $form
        ]);
    }

    #[Route('/show/{id}', name: 'app_assessment_show', methods: ['GET'])]
    public function show(Assessment $assessment): Response
    {
        $patinet = $assessment->getPatient();
        return $this->render('assessment/show.html.twig', [
            'assessment' => $assessment,
            'patient'=> $patinet 
        ]);
    }

    #[Route('/{id}/edit', name: 'app_assessment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Assessment $assessment, AssessmentRepository $assessmentRepository): Response
    {

       
        $form = $this->createForm(AssessmentType::class, $assessment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patient = $assessment->getPatient();

            $note = $form['note']->getData();       
            if($note == null ){

              $this->addFlash('danger','Patient diagnosis can not be null!'); 
              return $this->redirectToRoute('patient_show',["id"=>$patient->getId()]);

              }
            $assessment->setPatient($patient);
            $assessment->setCreatedAt(new DateTime());
            $assessment->setPhyscian($this->getUser());
            $assessmentRepository->add($assessment);
            $this->addFlash('success','Assessment updated successfully!');
          return $this->redirectToRoute('patient_show',["id"=>$patient->getId()]);
          
        }

        return $this->renderForm('assessment/edit.html.twig', [
            'assessment' => $assessment,
            'patient'=>$assessment->getPatient(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_assessment_delete', methods: ['POST'])]
    public function delete(Request $request, Assessment $assessment, AssessmentRepository $assessmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$assessment->getId(), $request->request->get('_token'))) {
            $assessmentRepository->remove($assessment);
        }

        return $this->redirectToRoute('app_assessment_index', [], Response::HTTP_SEE_OTHER);
    }
}
