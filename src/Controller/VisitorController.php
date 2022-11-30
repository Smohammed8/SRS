<?php

namespace App\Controller;

use App\Entity\Visitor;
use App\Entity\User;
use App\Form\VisitorType;
use App\Repository\VisitorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Patient as EntityPatientType;
use Knp\Component\Pager\PaginatorInterface;
use DateInterval;
use DateTime;

#[Route('/attendant')]
class VisitorController extends AbstractController
{
    #[Route('/', name: 'visitor_index', methods: ['GET'])]
    public function index(VisitorRepository $visitorRepository,Request $request, PaginatorInterface $paginator): Response
    {


        $queryBuilder =  $visitorRepository->getQuery($request->query->get('search'));
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         10
        );


        return $this->render('visitor/index.html.twig', [
            'visitors' => $data,
        ]);
    }

    #[Route('/new/{id}', name: 'visitor_new', methods: ['GET', 'POST'])]
    public function new(EntityPatientType $entityPatientType,Request $request, EntityManagerInterface $entityManager): Response
    {
        $visitor = new Visitor();
      //  $patient = $visitor->getPatient();
        $form = $this->createForm(VisitorType::class, $visitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $visitor->setDateOfVisit (new \DateTimeImmutable()); // this is date visiting
            $dt = new \DateTimeImmutable();
            $exitDate =  $dt->add(new DateInterval(('PT30M')));

             /* $dt = new \DateTimeImmutable();
             $newDate=  $dt->sub(new DateInterval(('P10D'))); // subtract 10 days
              echo $newDate;
              */
            $visitor->setExitTime ($exitDate); // final visiror
       
            $visitor->setAdmission(null);
            $visitor->setPatient($entityPatientType);
            $visitor->setUser($this->getUser());
            $entityManager->persist($visitor);
            $entityManager->flush();

            return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);

          //  return $this->redirectToRoute('visitor_index', [], Response::HTTP_SEE_OTHER);
        }
        
return $this->renderForm('visitor/new.html.twig', [
            'visitor' => $visitor,
            'form' => $form,
            'patient' =>$entityPatientType
        ]);
    }



    #[Route('/visitor_view/{id}', name: 'visitor_view', methods: ['GET','POST'])]
    public function getVisitors(VisitorRepository $visitorReposito,EntityPatientType $entityPatientType, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder =  $visitorReposito->getVisitors($entityPatientType,$request->query->get('search'));
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         3
      );

      return $this->render('visitor/view_visirors.html.twig', [
            'visitors' => $data,
            'patient' =>$entityPatientType
        
        ]);
    }



    #[Route('/{id}', name: 'visitor_show', methods: ['GET'])]
    public function show(Visitor $visitor): Response
    {
        return $this->render('visitor/show.html.twig', [
            'visitor' => $visitor,
        ]);
    }

    #[Route('/{id}/edit', name: 'visitor_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Visitor $visitor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VisitorType::class, $visitor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('visitor_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('visitor/edit.html.twig', [
            'visitor' => $visitor,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'visitor_delete', methods: ['POST'])]
    public function delete(Request $request, Visitor $visitor, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$visitor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($visitor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('visitor_index', [], Response::HTTP_SEE_OTHER);
    }
}
