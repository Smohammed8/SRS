<?php

namespace App\Controller;

use App\Entity\CourseType;
use App\Form\CourseTypeType;
use App\Repository\CourseTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/course-type')]
class CourseTypeController extends AbstractController
{
    #[Route('/', name: 'app_course_type_index', methods: ['GET'])]
    public function index(CourseTypeRepository $courseTypeRepository): Response
    {
        return $this->render('course_type/index.html.twig', [
            'course_types' => $courseTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_course_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CourseTypeRepository $courseTypeRepository): Response
    {
        $courseType = new CourseType();
        $form = $this->createForm(CourseTypeType::class, $courseType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseTypeRepository->add($courseType);
            return $this->redirectToRoute('app_course_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('course_type/new.html.twig', [
            'course_type' => $courseType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_type_show', methods: ['GET'])]
    public function show(CourseType $courseType): Response
    {
        return $this->render('course_type/show.html.twig', [
            'course_type' => $courseType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_course_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CourseType $courseType, CourseTypeRepository $courseTypeRepository): Response
    {
        $form = $this->createForm(CourseTypeType::class, $courseType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseTypeRepository->add($courseType);
            return $this->redirectToRoute('app_course_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('course_type/edit.html.twig', [
            'course_type' => $courseType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_type_delete', methods: ['POST'])]
    public function delete(Request $request, CourseType $courseType, CourseTypeRepository $courseTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$courseType->getId(), $request->request->get('_token'))) {
            $courseTypeRepository->remove($courseType);
        }

        return $this->redirectToRoute('app_course_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
