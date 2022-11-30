<?php

namespace App\Controller;

use App\Entity\Unit;
use App\Form\UnitType;
use App\Repository\UnitRepository;
use App\Repository\WardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
//use App\Entity\Ward;
//use App\Entity\Bed;
use App\Entity\Ward as EntityWardType;

#[Route('/unit')]
class UnitController extends AbstractController
{
    #[Route('/{id}', name: 'unit_index', methods: ['GET', 'POST'])]
    public function index(EntityWardType $entityWardType, UnitRepository $unitRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $result = $unitRepository->getUnitsAsArray($entityWardType)->getArrayResult();
            return $this->json([
                'success' => true,
                'data' => $result
            ]);
        }
        $queryBuilder = $unitRepository->getUnits_in_ward($entityWardType, $request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('unit/index.html.twig', [
            'units' => $data,
            'ward' => $entityWardType
        ]);
    }

    #[Route('/{id}', name: 'units_in_ward', methods: ['GET'])]
    public function units_in_wards(EntityWardType $entityWardType, UnitRepository $unitRepository, Request $request, PaginatorInterface $paginator): Response
    {
        //dd($entityWardType);
        $wardID =   $entityWardType->getId();

        $queryBuilder = $unitRepository->getUnits_in_ward($entityWardType, $request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('unit/index.html.twig', [
            'units' => $data,
            'ward' => $entityWardType
        ]);
    }

    #[Route('/new/{id}', name: 'unit_new', methods: ['POST', 'GET'])]
    public function new(EntityWardType $entityWardType, Request $request, EntityManagerInterface $entityManager): Response
    {
        $unit = new Unit();
        // $form = $this->createForm(UnitType::class, $unit);
        $form = $this->createForm(UnitType::class, $unit, ['action' => $this->generateUrl('unit_new', ['id' => $entityWardType->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $unit->setWard($entityWardType);
            $entityManager->persist($unit);
            $entityManager->flush();

            return $this->redirectToRoute('unit_index', ["id" => $entityWardType->getId()]);

            // return $this->redirectToRoute('unit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('unit/new.html.twig', [
            'unit' => $unit,
            'form' => $form,
            'ward' => $entityWardType
        ]);
    }

    #[Route('/show/{id}', name: 'unit_show', methods: ['GET', 'POST'])]
    public function show(Unit $unit): Response
    {
        // $entityWardType = $unit->getWard();

        return $this->render('unit/show.html.twig', [
            'unit' => $unit,
            'ward' =>  $unit->getWard()
        ]);
    }

    #[Route('/{id}/edit', name: 'unit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Unit $unit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UnitType::class, $unit, ['action' => $this->generateUrl('unit_edit', ['id' => $unit->getId()])]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityWardType = $unit->getWard();
            $entityManager->flush();
            return $this->redirectToRoute('unit_index', ["id" =>$entityWardType->getId()]);
        }
        return $this->renderForm('unit/edit.html.twig', [
            'unit' => $unit,
            'form' => $form,
            'ward' => $unit->getWard()
        ]);
    }


    #[Route('/{id}', name: 'unit_delete', methods: ['POST'])]
    public function delete(Request $request, Unit $unit, EntityManagerInterface $entityManager): Response
    {
        $entityWardType = $unit->getWard();
        if ($this->isCsrfTokenValid('delete'.$unit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($unit);
            $entityManager->flush();
            $this->addFlash('success',"Successfully deleted");
        }
        return $this->redirectToRoute('unit_index', ["id" =>$entityWardType->getId()]);

    }

}
