<?php

namespace App\Controller;

use App\Entity\Unit;
use App\Entity\Ward;
use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Unit as EntityUnitType;

#[Route('/room')]
class RoomController extends AbstractController
{
    #[Route('/{id}', name: 'room_index', methods: ['GET'])]
    public function index(EntityUnitType $entityUnitType, RoomRepository $roomRepository,Request $request, PaginatorInterface $paginator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $result =$roomRepository->getRooms_in_unit($entityUnitType)->getArrayResult();
            return $this->json([
                'success' => true,
                'data' => $result
            ]);
        }
        $queryBuilder = $roomRepository->getRooms_in_unit($entityUnitType,$request->query->get('search'));
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         10
      );

        return $this->render('room/index.html.twig', [
            'rooms' => $data,
            'unit'=> $entityUnitType
        ]);
    }

    #[Route('/{id}', name: 'rooms_in_unit', methods: ['GET'])]
    public function show_rooms_in_unit( EntityUnitType $entityUnitType, RoomRepository $roomRepository,Request $request, PaginatorInterface $paginator): Response
    {

        $queryBuilder = $roomRepository->getRooms_in_unit($entityUnitType,$request->query->get('search'));
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         10
      );

        return $this->render('room/index.html.twig', [
            'rooms' => $data,
             'unit'=>$entityUnitType
        ]);
    }

    #[Route('/new/{id}', name: 'room_new', methods: ['GET', 'POST'])]
    public function new(EntityUnitType $entityUnitType,Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = new Room();
        $form = $this->createForm(RoomType::class, $room,['action'=>$this->generateUrl('room_new',['id'=>$entityUnitType->getId()])]);
       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $room->setUnit($entityUnitType);
            $entityManager->persist($room);
            $entityManager->flush();

            return $this->redirectToRoute('room_index',["id"=>$entityUnitType->getId()]);
        }

        return $this->renderForm('room/new.html.twig', [
            'room' => $room,
            'form' => $form,
            'unit'=> $entityUnitType
        ]);
    }
    #[Route('/show/{id}', name: 'room_show', methods: ['GET', 'POST'])]
    public function show(Room $room): Response
    
    {
   
        return $this->render('room/show.html.twig', [
            'room' => $room,
            'unit' =>  $room->getUnit()
        ]);
    }

    #[Route('/edit/{id}', name: 'room_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Room $room, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RoomType::class, $room,['action'=>$this->generateUrl('room_edit',['id'=>$room->getId()])]);
          $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityUnitType = $room->getUnit();
            $entityManager->flush();

            return $this->redirectToRoute('room_index',["id"=>$entityUnitType->getId()]);
        }
        return $this->renderForm('room/edit.html.twig', [
            'room' => $room,
            'form' => $form,
            'unit'=> $room->getUnit()
        ]);
    }
    #[Route('/{id}', name: 'room_delete', methods: ['post'])]
    public function delete(Request $request, Room $room, EntityManagerInterface $entityManager): Response
    {
        $entityUnitType = $room->getUnit();
     
        if ($this->isCsrfTokenValid('delete'.$room->getId(), $request->request->get('_token'))) {
            $entityManager->remove($room);
            $entityManager->flush();
            $this->addFlash('success',"Successfully deleted");
        }
        return $this->redirectToRoute('room_index',["id"=>$entityUnitType->getId()]);

       // return $this->redirectToRoute('room_index', [], Response::HTTP_SEE_OTHER);
    }
}
