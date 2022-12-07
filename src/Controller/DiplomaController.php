<?php

namespace App\Controller;

use App\Entity\Diploma;
use App\Form\DiplomaType;
use App\Repository\DiplomaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Student as EntityStudentType;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/diploma')]
class DiplomaController extends AbstractController
{
    #[Route('/', name: 'app_diploma_index', methods: ['GET'])]
    public function index(DiplomaRepository $diplomaRepository): Response
    {
        return $this->render('diploma/index.html.twig', [
            'diplomas' => $diplomaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_diploma_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DiplomaRepository $diplomaRepository): Response
    {
        $diploma = new Diploma();
        $form = $this->createForm(DiplomaType::class, $diploma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diplomaRepository->add($diploma);
            return $this->redirectToRoute('app_diploma_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('diploma/new.html.twig', [
            'diploma' => $diploma,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_diploma_view', methods: ['GET'])]

    public function view_diploma(EntityStudentType $entityStudentType, DiplomaRepository $diplomaRepository,Request $request, PaginatorInterface $paginator): Response

    {
        $queryBuilder = $diplomaRepository->getDiploma($entityStudentType);
        $data = $paginator->paginate($queryBuilder, 
          $request->query->getInt('page', 1),
            1
         );

    return $this->render('diploma/index.html.twig', [
        'diplomas' => $data,
        'student' =>$entityStudentType
    ]);
}


    #[Route('/new/{id}', name: 'add_diploma_new', methods: ['GET', 'POST'])]
public function deploma(EntityStudentType $entityStudentType, Request $request,  DiplomaRepository $diplomaRepository): Response
{
 
    $diploma = new Diploma();
    $form = $this->createForm(DiplomaType::class, $diploma);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $diploma->setStudent($entityStudentType);
        $diplomaRepository->add($diploma);

        $this->addFlash('success', 'Tha student diploma information has been successfully created!');   
        return $this->redirectToRoute('app_student_show',["id"=>$entityStudentType->getId()]);  
          }
          return $this->renderForm('diploma/new.html.twig', [
         'student' => $entityStudentType,
         'diploma' => $diploma,
         'form' => $form,
     
    ]);
}


    #[Route('/{id}', name: 'app_diploma_show', methods: ['GET'])]
    public function show(Diploma $diploma): Response
    {
        return $this->render('diploma/show.html.twig', [
            'diploma' => $diploma,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_diploma_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Diploma $diploma, DiplomaRepository $diplomaRepository): Response
    {
        $form = $this->createForm(DiplomaType::class, $diploma);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student = $diploma->getStudent();
            $diplomaRepository->add($diploma);
            $this->addFlash('success', 'Successfully updated!');   
            return $this->redirectToRoute('app_diploma_view',["id"=>$student->getId()]);
        }

        return $this->renderForm('diploma/edit.html.twig', [
            'diploma' => $diploma,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_diploma_delete', methods: ['POST'])]
    public function delete(Request $request, Diploma $diploma, DiplomaRepository $diplomaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diploma->getId(), $request->request->get('_token'))) {
            $diplomaRepository->remove($diploma);
        }

        return $this->redirectToRoute('app_diploma_index', [], Response::HTTP_SEE_OTHER);
    }
}
