<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Program;
use App\Entity\ProgramLevel;
use App\Entity\Modality;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Knp\Component\Pager\PaginatorInterface;

// use Andegna\DateTime;
use DateTime;
use Andegna\DateTimeFactory;
use Andegna\DateTime as et_date;
use Andegna\DateTimeInterface;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TelType;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/', name: 'app_student_index', methods: ['GET'])]
    public function index( Request $request, PaginatorInterface $paginator,StudentRepository $studentRepository): Response
    {





    $form = $this->createFormBuilder()
        ->setMethod('Get')



        ->add('program', EntityType::class, [
            'class' =>Program::class,
            'query_builder' => function (EntityRepository $err) {
                $result = $err->createQueryBuilder('e')
             
                       ->orderBy('e.name', 'ASC');   
    
                 
                return $result;
            },
        
            'placeholder' => "Select program...",
        ])



        ->add('modality', EntityType::class, [
            'class' =>Modality::class,
            'query_builder' => function (EntityRepository $err) {
                $result = $err->createQueryBuilder('e')
             
                       ->orderBy('e.name', 'ASC');   
    
                 
                return $result;
            },
        
            'placeholder' => "Select modality...",
        ])

        ->add('programLevel', EntityType::class, [
            'class' =>ProgramLevel::class,
            'query_builder' => function (EntityRepository $err) {
                $result = $err->createQueryBuilder('e')
             
                       ->orderBy('e.name', 'ASC');   
    
                 
                return $result;
            },
        
            'placeholder' => "P
            
            rogram level...",
        ])

        ->add('gender', ChoiceType::class, array(  
            'choices'  => array(
            'Select Sex..' =>'',
            'Male' =>'M',
            'Female' =>'F'),
             'required'=>true,
            // 'mapped'=>false,
             'attr'=> array(
              'class'=>'form-control select2'

             )
    ))

        ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $geneder   = $form->get('geneder')->getData();
            $program  = $form->get('program')->getData();
            $programlevel  = $form->get('programLevel')->getData();

            if ($geneder== null ) {

                $this->addFlash('warning', 'Start date must be before from the end date!');
                return $this->redirectToRoute('patient_index', [], Response::HTTP_SEE_OTHER);
            }

            $queryBuilder = $studentRepository->getFilter($geneder,$program,$programlevel);
        } 
        else {
          
            $queryBuilder = $studentRepository->getQuery($request->query->get('search'));
        }
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('student/index.html.twig', [
            'students' => $data,
            'form' => $form,
            'total_students'   =>$studentRepository->total_students(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_student_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StudentRepository $studentRepository): Response
    {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $student->setCreatedBy($this->getUser());
            $student->setCreatedAt(new \DateTimeImmutable());
            $studentRepository->add($student);
            $this->addFlash('success', "The student has been successfully registered!");
            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/new.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_show', methods: ['GET'])]
    public function show(Student $student,Request $request, PaginatorInterface $paginator): Response
    {
    
        // $outcomes = $outcomeRepository->findAll();
   
        // $referals = $referralsRepository->findAll();

        return $this->render('student/show.html.twig', [
         'student' => $student,
        // 'outcomes' => $outcomes,
        // 'referals' => $referals,
        // 'referrals'     =>$referralsRepository->findAll(),
       
        // 'encounters'      =>$paginator->paginate($encounterRepository->getEncounter($patient), $request->query->getInt('page', 1),  1   ),
        // 'admimssions'      =>$paginator->paginate($admimssionRepository->getAdmission($patient), $request->query->getInt('page', 1),  1   ),
        // 'appointments'    =>$paginator->paginate($appointmentRepository->getAppointment($patient), $request->query->getInt('page', 1),  1   ),
            
        // 'encounter_types'  =>$encounterTypeRepository->findAll(),
        // 'departments'    =>$departmentRepository->findAll(),
        // 'reason_of_referrals'=>$reasonOfReferralsRepository->findAll(),
        // 'disease_categorys'=>$diseaseCategoryRepository->findAll(),
        // 'price' => $bedPriceReppository->getPrice(),
        // 'assessments'=>$assessmentRepository->getDiagnosis($patient)
         ]
        );
        

 
    }

    #[Route('/{id}/edit', name: 'app_student_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $student->setCreatedBy($this->getUser());
          //  $student->setCreatedAt(new \DateTimeImmutable();

            $studentRepository->add($student);
            return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('student/edit.html.twig', [
            'student' => $student,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_student_delete', methods: ['POST'])]
    public function delete(Request $request, Student $student, StudentRepository $studentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$student->getId(), $request->request->get('_token'))) {
            $studentRepository->remove($student);
        }

        return $this->redirectToRoute('app_student_index', [], Response::HTTP_SEE_OTHER);
    }
}
