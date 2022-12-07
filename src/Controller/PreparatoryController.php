<?php

namespace App\Controller;

use App\Entity\Preparatory;
use App\Form\PreparatoryType;
use App\Repository\PreparatoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Student as EntityStudentType;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;


#[Route('/preparatory')]
class PreparatoryController extends AbstractController
{
    #[Route('/', name: 'app_preparatory_index', methods: ['GET'])]
    public function index(PreparatoryRepository $preparatoryRepository): Response
    {
        return $this->render('preparatory/index.html.twig', [
            'preparatories' => $preparatoryRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_preparatory_view', methods: ['GET'])]

    public function view_prepartoty(EntityStudentType $entityStudentType, PreparatoryRepository $preparatoryRepository,Request $request, PaginatorInterface $paginator): Response

    {
       
        $queryBuilder = $preparatoryRepository->getPreparatory($entityStudentType);
        $data = $paginator->paginate($queryBuilder, 
          $request->query->getInt('page', 1),
            1
         );

    return $this->render('preparatory/index.html.twig', [
        'preparatories' => $data,
        'student' =>$entityStudentType
    ]);
}


#[Route('/new/{id}', name: 'add_preparatory_new', methods: ['GET', 'POST'])]
public function preparatory(EntityStudentType $entityStudentType, Request $request, PreparatoryRepository $preparatoryRepository): Response
{
 
    $preparatory = new Preparatory();
    $form = $this->createForm(PreparatoryType::class, $preparatory);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $preparatory->setStudent($entityStudentType);
        $preparatoryRepository->add($preparatory);


        $this->addFlash('success', 'Tha student preparatory information has been successfully created!');   
        return $this->redirectToRoute('app_student_show',["id"=>$entityStudentType->getId()]);  
          }
          return $this->renderForm('preparatory/new.html.twig',[
       'student' => $entityStudentType,
        'preparatories' => $preparatory,
        'form' => $form,
     
    ]);
}



    #[Route('/new', name: 'app_preparatory_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PreparatoryRepository $preparatoryRepository): Response
    {
        $preparatory = new Preparatory();
        $form = $this->createForm(PreparatoryType::class, $preparatory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $preparatoryRepository->add($preparatory);
            return $this->redirectToRoute('app_preparatory_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('preparatory/new.html.twig', [
            'preparatory' => $preparatory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_preparatory_show', methods: ['GET'])]
    public function show(Preparatory $preparatory): Response
    {

        $student = $preparatory->getStudent();
        return $this->render('preparatory/show.html.twig', [
            'preparatory' => $preparatory,
            'student' =>$student
        ]);
    }

    #[Route('/{id}/edit', name: 'app_preparatory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Preparatory $preparatory, PreparatoryRepository $preparatoryRepository): Response
    {
        $form = $this->createForm(PreparatoryType::class, $preparatory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $student = $preparatory ->getStudent();
            $preparatoryRepository->add($preparatory);

            $this->addFlash('success', 'Successfully updated!');   
            return $this->redirectToRoute('app_preparatory_view',["id"=>$student->getId()]);  

           
        }

        return $this->renderForm('preparatory/edit.html.twig', [
            'preparatory' => $preparatory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_preparatory_delete', methods: ['POST'])]
    public function delete(Request $request, Preparatory $preparatory, PreparatoryRepository $preparatoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$preparatory->getId(), $request->request->get('_token'))) {
            $preparatoryRepository->remove($preparatory);
        }

        return $this->redirectToRoute('app_preparatory_index', [], Response::HTTP_SEE_OTHER);
    }
}
