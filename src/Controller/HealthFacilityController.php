<?php

namespace App\Controller;

use App\Entity\HealthFacility;
use App\Form\HealthFacilityType;
use App\Repository\HealthFacilityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/health/facility')]
class HealthFacilityController extends AbstractController
{
    #[Route('/', name: 'health_facility_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request,HealthFacilityRepository $healthFacilityRepository): Response
    {

        $queryBuilder =  $healthFacilityRepository->getQuery($request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('health_facility/index.html.twig', [
            'health_facilities' =>$data,
        ]);
    }

    #[Route('/new', name: 'health_facility_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $healthFacility = new HealthFacility();
        $form = $this->createForm(HealthFacilityType::class, $healthFacility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($healthFacility);
            $entityManager->flush();

            return $this->redirectToRoute('health_facility_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('health_facility/new.html.twig', [
            'health_facility' => $healthFacility,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'health_facility_show', methods: ['GET'])]
    public function show(HealthFacility $healthFacility): Response
    {
        return $this->render('health_facility/show.html.twig', [
            'health_facility' => $healthFacility,
        ]);
    }

    #[Route('/{id}/edit', name: 'health_facility_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HealthFacility $healthFacility, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HealthFacilityType::class, $healthFacility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('health_facility_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('health_facility/edit.html.twig', [
            'health_facility' => $healthFacility,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'health_facility_delete', methods: ['POST'])]
    public function delete(Request $request, HealthFacility $healthFacility, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$healthFacility->getId(), $request->request->get('_token'))) {
            $entityManager->remove($healthFacility);
            $entityManager->flush();
        }

        return $this->redirectToRoute('health_facility_index', [], Response::HTTP_SEE_OTHER);
    }
}
