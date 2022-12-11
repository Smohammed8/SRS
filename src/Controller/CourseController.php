<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Slip;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Helper\DomPrint;
use Knp\Component\Pager\PaginatorInterface;

use App\Entity\Program as EntityProgramType;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/course')]
class CourseController extends AbstractController
{
    #[Route('/', name: 'app_course_index', methods: ['GET'])]
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }





    #[Route('/{id}', name: 'app_course_view', methods: ['GET'])]
    public function view_course(PaginatorInterface $paginator, EntityProgramType $entityProgramType,CourseRepository $courseRepository,Request $request): Response
    {

        $queryBuilder =  $courseRepository->getCourse($entityProgramType, $request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
            return $this->render('course/view_course.html.twig', [
                'courses' => $data,
                'program' =>$entityProgramType
        ]);
    

    }


    #[Route('/course_view/{id}', name: 'course_view', methods: ['GET'])]
    public function courseShow( Request $request, Slip $slip, PaginatorInterface $paginator,CourseRepository $courseRepositor): Response
    {
        
        $queryBuilder =  $courseRepositor->getCourses($slip->getYear(),$slip->getSemester(), $slip->getProgram());
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         15
      );

      
      return $this->render('course/slip_course.html.twig', [
            'courses' => $data,
            'slip' =>$slip,
            'student' =>$slip->getStudent(),
            'semester' =>$slip->getSemester(),
            'year' =>$slip->getYear(),
            'program' =>$slip->getProgram()
          
        
        ]);
    }

    // #[Route('/addNew/{id}', name: 'app_add_course_new', methods: ['GET', 'POST'])]
    // public function addNew( EntityProgramType $entityProgramType, Request $request, CourseRepository $courseRepository): Response
    // {
    //     $course = new Course();
    //     $form = $this->createForm(CourseType::class, $course);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $courseRepository->add($course);
    //       //  return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);

    //         return $this->redirectToRoute('app_course_index',["id"=>$entityProgramType->getId()]);
    //     }

    //     return $this->renderForm('course/new.html.twig', [
    //         'course' => $course,
    //         'form' => $form,
    //         'program' =>$entityProgramType
    //     ]);
    // }

    #[Route('/add/new_course', name: 'app_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CourseRepository $courseRepository, EntityManagerInterface $entityManager): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRepository->add($course);
            $this->addFlash('success','Course successfully saved');

            return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_show', methods: ['GET'])]
    public function show(Course $course): Response
    {
        return $this->render('course/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRepository->add($course);
            $this->addFlash('success','Course successfully edited');
            return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('course/edit.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, CourseRepository $courseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $courseRepository->remove($course);
        }

        return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
