<?php

namespace App\Controller;

use App\Entity\HightSchool;
use App\Form\HightSchoolType;
use App\Repository\HightSchoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Student as EntityStudentType;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/hight-school')]
class HightSchoolController extends AbstractController
{
    #[Route('/', name: 'app_hight_school_index', methods: ['GET'])]
    public function index(HightSchoolRepository $hightSchoolRepository): Response
    {
        return $this->render('hight_school/index.html.twig', [
            'hight_schools' => $hightSchoolRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_hight_school_view', methods: ['GET'])]

    public function view_highs_chool(EntityStudentType $entityStudentType, HightSchoolRepository $hightSchoolRepository,Request $request, PaginatorInterface $paginator): Response

    {
       
        $queryBuilder = $hightSchoolRepository->getHighSchool($entityStudentType);
        $data = $paginator->paginate($queryBuilder, 
          $request->query->getInt('page', 1),
            1
         );

 return $this->render('hight_school/index.html.twig', [
        'hight_schools' => $data,
        'student' =>$entityStudentType,
    ]);
}


    #[Route('/new', name: 'app_hight_school_new', methods: ['GET', 'POST'])]
    public function new(Request $request, HightSchoolRepository $hightSchoolRepository): Response
    {
        $hightSchool = new HightSchool();
        $form = $this->createForm(HightSchoolType::class, $hightSchool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hightSchoolRepository->add($hightSchool);
            return $this->redirectToRoute('app_hight_school_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hight_school/new.html.twig', [
            'hight_school' => $hightSchool,
            'form' => $form,
        ]);
    }

    #[Route('/new/{id}', name: 'app_highschool_new', methods: ['GET', 'POST'])]
    public function highschool(EntityStudentType $entityStudentType, Request $request, HightSchoolRepository $hightSchoolRepository): Response
    {
      $hightSchool = new HightSchool();
            $form = $this->createForm(HightSchoolType::class, $hightSchool);
            $form->handleRequest($request);

           if ($form->isSubmitted() && $form->isValid()) {
            $hightSchool->setStudent($entityStudentType);
            $hightSchoolRepository->add($hightSchool);
         
            $this->addFlash('success', 'Tha student high school information has been successfully created!');   
            return $this->redirectToRoute('app_student_show',["id"=>$entityStudentType->getId()]);  
              }
              return $this->renderForm('hight_school/new.html.twig',[
           'student' => $entityStudentType,
            'hightSchool' => $hightSchool,
            'form' => $form,
         
        ]);
    }

    #[Route('/{id}', name: 'app_hight_school_show', methods: ['GET'])]
    public function show(HightSchool $hightSchool): Response
    {
        $student = $hightSchool->getStudent();
        return $this->render('hight_school/show.html.twig', [
            'hight_school' => $hightSchool,
            'student'=> $student
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_hight_school_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HightSchool $hightSchool, HightSchoolRepository $hightSchoolRepository): Response
    {
        $form = $this->createForm(HightSchoolType::class, $hightSchool);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student = $hightSchool->getStudent();
            $hightSchoolRepository->add($hightSchool);

            $this->addFlash('success', 'Successfully updated!');   
            return $this->redirectToRoute('app_hight_school_view',["id"=>$student->getId()]);

           
        }

        return $this->renderForm('hight_school/edit.html.twig', [
            'hight_school' => $hightSchool,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_hight_school_delete', methods: ['POST'])]
    public function delete(Request $request, HightSchool $hightSchool, HightSchoolRepository $hightSchoolRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hightSchool->getId(), $request->request->get('_token'))) {
            $hightSchoolRepository->remove($hightSchool);
        }

        return $this->redirectToRoute('app_hight_school_index', [], Response::HTTP_SEE_OTHER);
    }
}
