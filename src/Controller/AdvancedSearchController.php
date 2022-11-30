<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Andegna\DateTimeInterface;
use Andegna\DateTime;
use Andegna\DateTimeFactory;
use App\Entity\Patient;
use App\Entity\User;
use App\Entity\Ward;
use App\Entity\Unit;
use App\Entity\Room;
use App\Entity\Bed;
use App\Entity\Outcome;
use App\Repository\PatientRepository;
use App\Repository\WardRepository;
use App\Repository\UnitRepository;
use App\Repository\RoomRepository;
use App\Repository\BedRepository;
use App\Repository\AdmimssionRepository;

Use App\Repository\AdmimssionTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;


class AdvancedSearchController extends AbstractController
{
    #[Route('/advancedSearch', name: 'app_advanced_search')]
    public function advancedSearch(AdmimssionRepository $admimssionRepository,PaginatorInterface $paginator,Request $request): Response
    {


        $form = $this->createFormBuilder()
     
          // ->add('type', ChoiceType::class, array('choices'  => array('............' => '',  'Generate as PDF' => 1,  'Export as Excel' => '2')));
       
            ->add('ward', EntityType::class, [
                'class' => Ward::class,
                'required' =>false,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                    return $res;
                },

                //'placeholder' => "Select ward...",
            ])
      

            ->add('unit', EntityType::class, [
                'class' => Unit::class,
                'required' =>false,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                    return $res;
                },

              //  'placeholder' => "Sele t unit...",
            ]);
          
             $form = $form->getForm();
             $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
              $sdate  = $request->get('date');
              $ward   = $form->get('ward')->getData();
              $unit   = $form->get('unit')->getData();
              $cdate = new \DateTime('now');
              $sd      = explode('/',$sdate);
              $dt  = DateTimeFactory::of($sd[2], $sd[1], $sd[0]);
            
            if ($cdate  < $dt->toGregorian()) {

                $this->addFlash('danger', 'Date of adminssion cannot be future!');
                return $this->redirectToRoute('app_advanced_search', [], Response::HTTP_SEE_OTHER);
              }
            $queryBuilder =  $admimssionRepository->getAdvancedSearch($ward, $unit,$dt->toGregorian());
            $data = $paginator->paginate(
               $queryBuilder,
               $request->query->getInt('page', 1),
               15
           ); 

           return $this->render('advanced_search/search_result.html.twig', [
            'admimssions'=>$data,
            'ward'=>$ward,
            'unit'=>$unit,
            'date'=>$sdate ,
            'form' => $form->createView()
           ]);

        }
         
        return $this->render('advanced_search/index.html.twig', [
            'form' => $form->createView()
        ]);
    
}
}
