<?php

namespace App\Controller;

use App\Entity\Bed;
use App\Entity\Ward;
use App\Entity\User;
use App\Entity\Unit;
use App\Form\BedType;
use App\Form\Wardype;
use App\Form\UnitType;
use App\Form\RoomType;
use App\Repository\BedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Room;
use App\Helper\Constants;
use App\Entity\Room as EntityRoomType;
use App\Repository\WardRepository;

#[Route('/bed')]
class BedController extends AbstractController
{


    
    #[Route('/{id}', name: 'bed_index', methods: ['GET'])]
    public function index(EntityRoomType $entityRoomType, BedRepository $bedRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $result = $bedRepository->getFreeBeds($entityRoomType)->getArrayResult();
            return $this->json([
                'success' => true,
                'data' => $result
            ]);
        } else {
            $queryBuilder =  $bedRepository->getBeds($entityRoomType, $request->query->get('search'));
            $data = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                10
            );
        }
        return $this->render('bed/index.html.twig', [
            'beds' => $data,
            'room' => $entityRoomType
        ]);
    }

 

    #[Route('/view/beds', name: 'view_beds', methods: ['GET'])]
    public function getAllBeds(BedRepository $bedRepository, Request $request, PaginatorInterface $paginator): Response
    {

        $queryBuilder =  $bedRepository->getQuery($request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('bed/bed_index.html.twig', [
            'beds' => $data,
           
        ]);
    }

    #[Route('/{id}', name: 'beds_in_room', methods: ['GET', 'POST'])]
    public function show_beds_in_room(EntityRoomType $entityRoomType, BedRepository $bedRepository, Request $request, PaginatorInterface $paginator): Response
    {


        $queryBuilder = $bedRepository->getBeds($entityRoomType, $request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('bed/index.html.twig', [
            'beds' => $data,
            'room' => $entityRoomType
        ]);
    }
    #[Route('/new/{id}', name: 'bed_new', methods: ['GET', 'POST'])]
    public function new(EntityRoomType $entityRoomType, Request $request, EntityManagerInterface $entityManager): Response
    {
        $bed = new Bed();

        $form = $this->createForm(BedType::class, $bed, ['action' => $this->generateUrl('bed_new', ['id' => $entityRoomType->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bed->setRoom($entityRoomType);

            $bed->setAccessibility(Constants::FREE_BED);
            $bed->setCurrentStatus(Constants::NON_IMPENDING);
            $bed->setUpdatedAt(new \DateTimeImmutable());
            $bed->setRegisteredBy($this->getUser());

            $entityManager->persist($bed);
            $entityManager->flush();
            $this->addFlash('success', "Successfully saved");

            return $this->redirectToRoute('bed_index', ["id" => $entityRoomType->getId()]);

            //return $this->redirectToRoute('bed_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('bed/new.html.twig', [
            'bed' => $bed,
            'form' => $form,
            'room' => $entityRoomType
        ]);
    }


    #[Route('/add/new_bed', name: 'add_bed', methods: ['GET', 'POST'])]
    public function addNewBed(Request $request, EntityManagerInterface $entityManager,WardRepository $wardRepository): Response
    {
        $bed = new Bed();
        $form2 = $this->createFormBuilder()
            ->add('ward', EntityType::class, [
                'class' => Ward::class,
                'required' => true,
                // 'query_builder' => function (EntityRepository $er) {
                //     $res = $er->createQueryBuilder('u')->where('u.id = -1')
                //         ->orderBy('u.name', 'ASC');
                //     return $res;
                // },
                'attr' => array('class' => 'select2'),
                'placeholder' => "Select ward...",
            ])

            ->add('unit', EntityType::class, [
                'class' => Unit::class,
                'required' => true,
                // 'query_builder' => function (EntityRepository $er) {
                //     $res = $er->createQueryBuilder('u')
                //         ->orderBy('u.name', 'ASC');
                //         return null;
                //     return $res;
                // },
                'attr' => array('class' => 'select2'),
                'placeholder' => " Select unit...",
            ])

            ->add('name')
            
        //     ->add('hasOxgen', ChoiceType::class, array(   
        //         'choices'  => array(
        //           'Has the bed oxgen?' =>'',
        //           'No' =>'No',
        //           'Yes' =>'Yes',
                
        //       ),
        //       'required'=>true,
        //   ))


            ->add('isFunctional', ChoiceType::class, array(
                'attr' => array('class' => 'select2'),
                'choices'  => array(
                    //'Select bed functional Status...' =>'',
                    'Yes' => 'Yes',
                    'No' => 'No',
                ),
                'required' => true,
            ))
            ->add('type', ChoiceType::class, array(
                'attr' => array('class' => 'select2'),

                'choices'  => array(
                    //    'Select bed type...' =>'',
                    'Single' => 'Single',
                    'Double' => 'Double',
                ),
                'required' => true,
            ))


            ->add('room', EntityType::class, [
                'class' => Room::class,
                'attr' => array('class' => 'select2'),
                'required' => true,

                // 'query_builder' => function (EntityRepository $er) {
                //     $res = $er->createQueryBuilder('u')
                //     ->orderBy('u.name', 'ASC'); 
                //     return $res;
                // },

                'placeholder' => " Select room...",
            ])->getForm();
        $form2->handleRequest($request);
        if ($form2->isSubmitted()&& $form2->isValid()) {
            $room  = $form2->get('room')->getData();
            $name = $form2->get('name')->getData();
            $type = $form2->get('type')->getData();
            $isFunctional = $form2->get('isFunctional')->getData();
            $bed->setRoom($room);
            $bed->setName($name);
            $bed->setType($type);
            $bed->setIsFunctional($isFunctional);
            $bed->setAccessibility(Constants::FREE_BED);
            $bed->setCurrentStatus(Constants::NON_IMPENDING);
            $bed->setUpdatedAt(new \DateTimeImmutable());
            $bed->setRegisteredBy($this->getUser());
            $entityManager->persist($bed);
            $entityManager->flush();
            $this->addFlash('success', "Successfully saved");
            return $this->redirectToRoute('view_beds', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('bed/new2.html.twig', [
            'bed' => $bed,
            'form2' => $form2

        ]);
    }

    #[Route('/show/{id}', name: 'bed_show',  methods: ['GET', 'POST'])]
    public function show(Bed $bed, PaginatorInterface $paginator, Request $request): Response
    {
       ;
        return $this->render('bed/show.html.twig', [
            'bed' =>$bed,
            'room' => $bed->getRoom()
        ]);
    }

    #[Route('/{id}/edit', name: 'bed_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bed $bed, EntityManagerInterface $entityManager): Response
    {
     
        $form = $this->createForm(BedType::class, $bed, ['action' => $this->generateUrl('bed_edit', ['id' => $bed->getId()])]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $status  = $request->get('status');
        
            $entityRoomType = $bed->getRoom();
            $bed->setRoom($entityRoomType);

            $status  = $request->get('status');
            $impending  = $request->get('current_status');

            
            if( $status == 1 ){
                $bed->setAccessibility(Constants:: OCCUPIED_BED);
            }
            else{
                $bed->setAccessibility(Constants::FREE_BED);

            }
            if($impending ==0 ){

                $bed->setCurrentStatus(Constants::NON_IMPENDING);
             
            }
            else {

                $bed->setCurrentStatus(Constants::IMPENDING);
            }

            
          
              $isfunc  = $form->get('isFunctional')->getData();
           
              if($isfunc =='Yes')
              $bed->setIsFunctional(1);
              else 
              $bed->setIsFunctional(0);
                 
            $bed->setUpdatedAt(new \DateTimeImmutable());
            $bed->setRegisteredBy($this->getUser());
            $entityManager->flush();
            $this->addFlash('success', "Successfully updated");
            return $this->redirectToRoute('bed_index', ["id" => $entityRoomType->getId()]);
        }

        return $this->renderForm('bed/edit.html.twig', [
            'bed' => $bed,
            'form' => $form,
            'room' => $bed->getRoom()
        ]);
    }



    #[Route('/{id}', name: 'bed_delete', methods: ['POST'])]
    public function delete(Request $request, Bed $bed, EntityManagerInterface $entityManager): Response
    {
        $entityRoomType = $bed->getRoom();
        if ($this->isCsrfTokenValid('delete' . $bed->getId(), $request->request->get('_token'))) {
            $entityManager->remove($bed);
            $entityManager->flush();
            $this->addFlash('success',"Successfully deleted");
        }
        return $this->redirectToRoute('bed_index', ["id" => $entityRoomType->getId()]);
    }



}
