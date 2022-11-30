<?php

namespace App\Controller;

use Amp\Success;
use DateTime;
use App\Entity\Bed;
use App\Entity\Ward;
use App\Entity\Unit;
use App\Entity\Room;
use DateTimeImmutable;
use App\Entity\Patient;
use App\Helper\Constants;
use App\Entity\Admimssion;
use App\Form\AdmimssionType;
use App\Repository\BedRepository;
use App\Repository\RoomRepository;
use App\Repository\UnitRepository;
use App\Repository\OutcomeRepository;
use App\Repository\ReferralsRepository;
use App\Repository\AdmimssionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Patient as EntityPatientType;
use App\Repository\AdmimssionTypeRepository;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\AdmimssionType as  AdmimssionTypeType;
use App\Repository\WardRepository;
use PhpParser\Node\Stmt\Const_;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\PersistentCollection;

#[Route('/admimssion')]
class AdmimssionController extends AbstractController
{
    private $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'admimssion_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, AdmimssionRepository $admimssionRepository): Response
    {

        $queryBuilder =  $admimssionRepository->getQuery($request->query->get('search'));
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admimssion/index.html.twig', [
            'admimssions' => $data,
        ]);
    }

    #[Route('/{id}', name: 'view_admimssion', methods: ['GET'])]
    public function view_adminssion(EntityPatientType $entityPatientType,Request $request, PaginatorInterface $paginator, AdmimssionRepository $admimssionRepository): Response
    {
        
      $queryBuilder = $admimssionRepository->getAdmission($entityPatientType);
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            1
        );
        return $this->render('admimssion/view_adminssions.html.twig', [
            'admimssions' => $data,
            'patient' =>$entityPatientType
        ]);
    }
    #[Route('/waiting/list', name: 'waiting_info', methods: ['GET','POST'])]
    public function waiting_info(Request $request, PaginatorInterface $paginator, AdmimssionRepository $admimssionRepository): Response
    {

        $queryBuilder =  $admimssionRepository->getWaitingQuery();
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('admimssion/waiting_list.html.twig', [
            'admimssions' => $data,
        ]);
    }
////////////////////////////// waiting list shoud be included here //////////////////////////////////////////////////////////
    #[Route('/waiting_list/{id}', name: 'waiting_list', methods: ['GET'])]
    public function  getWaitingList(Ward $ward, Request $request, PaginatorInterface $paginator, AdmimssionRepository $admimssionRepository): Response
    {

         $wardName = $ward->getName();
         $ward = $this->getUser()->getWard();
        // $iswaiting = false; // is check = 0 are waiting list
         $queryBuilder =  $admimssionRepository->getWaitingList($ward); 
         $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
        12
       );
        return $this->render('admimssion/my_patients.html.twig', [
         'admimssions' => $data,
         'ward'=> $wardName
        ]);
    }
////////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/wait_lists/{id}', name: 'new_adminssions', methods: ['GET'])]
    public function  getNewAdminssions(Ward $ward, Request $request, PaginatorInterface $paginator, AdmimssionRepository $admimssionRepository): Response
    {
       $wardName = $ward->getName();
       $queryBuilder =  $admimssionRepository->getNewAdminsions($ward); 
       $data = $paginator->paginate(
        $queryBuilder,
        $request->query->getInt('page', 1),
        12
       );
        return $this->render('admimssion/my_patients.html.twig', [
         'admimssions' => $data,
         'ward'=> $wardName
        ]);
    }

    #[Route('/myAdmitted/{id}', name: 'myAdmitted', methods: ['GET'])]
    public function  getAdmitted(Ward $ward, Request $request, PaginatorInterface $paginator, AdmimssionRepository $admimssionRepository): Response
    {
           $wardName = $ward->getName();
          $queryBuilder =  $admimssionRepository->getAdmited($ward);
           $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('admimssion/my_patients.html.twig', [
            'admimssions' => $data,
            'ward'=> $wardName
             
        ]);
    }


    #[Route('/discharges/{id}', name: 'my_discharges', methods: ['GET'])]
    public function  getDischarged(Ward $ward,Request $request, PaginatorInterface $paginator, AdmimssionRepository $admimssionRepository): Response
    {
            $wardName = $ward->getName();
           $queryBuilder =  $admimssionRepository->getDischarges($ward);
            $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
            );
        return $this->render('admimssion/my_patients.html.twig', [
            'admimssions' => $data,
            'ward'=> $wardName
        ]);
    }

    #[Route('/{patient}/new/', name: 'admimssion_new', methods: ['POST'])]
    public function new(Request $request, BedRepository $bedRepository, AdmimssionRepository $admimssionRepository, AdmimssionTypeRepository $admimssionTypeRepository, EntityManagerInterface $entityManager, Patient $patient): Response
    {
           if ($request->getMethod() == 'POST') {
            $bedId = $request->get('bed');
           // $bed_status = $bedRepository->find($bedId);
            if ($bedId != null) {
                if ($request->get('re_admission') != 'none') {
                    $allAdmissions = $admimssionRepository->getLastAdmission($patient)->getResult();
                    $lastAdmission = count($allAdmissions) > 0 ? $allAdmissions[0] : null;
                    if ($lastAdmission == null || $lastAdmission->getAdmission() != null) {
                        $this->addFlash('warning', 'You can\'t re admit this patient!');
                        return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
                    }
                    $bed = $bedRepository->find($bedId);

                     $unit= $bed->getRoom()->getUnit();
                     $unit->setLastUpdated(new \DateTime('now'));
                     $entityManager->persist($unit);
                     $entityManager->flush();

                    $user = $this->security->getUser();
                    $admimssion = new Admimssion();
                    $admimssion->setPatient($patient);
                    $admimssion->setIsCheckedIn(false);
                    $patient->setStatus(constants::ADMITTED);
                    $admimssion->setBed($bed);
                    $admimssion->setUser($user);
                    $admimssion->setNeedOxgen($request->request->get('oxgen'));
                    $admimssion->setType($admimssionTypeRepository->find($request->get('type')));
                    $admimssion->setStatus(Constants::ADMISSION_ADMITTED);
                
                    $admimssion->setCreatedAt(new \DateTime('now'));
                    $this->addFlash('success', 'Tha patient has been added to waiting list successfully!');
                    $bed->setAccessibility(Constants::OCCUPIED_BED);
                    $bed->setCurrentStatus(Constants::NON_IMPENDING);
                    $entityManager->persist($patient);
                    $entityManager->flush();
                    $entityManager->persist($admimssion);
                    $entityManager->flush();
                    $lastAdmission->setAdmission($admimssion);
                    $entityManager->persist($bed);
                    $entityManager->flush();
                    $entityManager->persist($lastAdmission);
                    $entityManager->flush();
                    return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
                } else {
                    $bed = $bedRepository->find($bedId);

                    $unit= $bed->getRoom()->getUnit();
                    $unit->setLastUpdated(new \DateTime('now'));
                    $entityManager->persist($unit);
                    $entityManager->flush();

                    $user = $this->security->getUser();
                    $admimssion = new Admimssion();
                    $admimssion->setPatient($patient);
                    $admimssion->setIsCheckedIn(false);
                    $patient->setStatus(constants::ADMITTED);
                    $entityManager->persist($patient);
                    $entityManager->flush();
                    $admimssion->setBed($bed);
                    $admimssion->setUser($user);
                    $admimssion->setNeedOxgen($request->request->get('oxgen'));
                    $admimssion->setType($admimssionTypeRepository->find($request->get('type')));
                    
                    $admimssion->setStatus(Constants::ADMISSION_ADMITTED);
                    $admimssion->setCreatedAt(new \DateTime('now'));
                    $entityManager->persist($admimssion);
                    $entityManager->flush();
                    $this->addFlash('success', 'Tha patient has been added to waiting list successfully!');
                    $bed->setAccessibility(Constants::OCCUPIED_BED);
                    $bed->setCurrentStatus(Constants::NON_IMPENDING);
                    $entityManager->persist($bed);
                    $entityManager->flush();
                    return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
                  }
               }
            return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
        }
    }
   // #[Route('/{admimssion}/checked_in/', name: 'admimssion_checked_in', methods: ['POST'])]
    #[Route('/{id}', name: 'admimssion_checked_in', methods: ['POST'])]
    public function check_in(Request $request,  Admimssion $admimssion,EntityManagerInterface $entityManager): Response
    {
        $admimssion->setIsCheckedIn(true);
        $entityManager->persist($admimssion);
        $entityManager->flush();
        return $this->json([
            'success' => true,
            'data' => 'succedded',
        ]);
    }

    #[Route('/{id}', name: 'admission_toggle_impending_status', methods: ['POST'])]
    public function toggleImpending(Request $request, EntityManagerInterface $entityManager, Admimssion $admimssion, OutcomeRepository $outcomeRepository, ReferralsRepository $referralsRepository): Response
    {
        if ($admimssion->getBed()->getCurrentStatus() == Constants::IMPENDING) {
            $admimssion->getBed()->setCurrentStatus(Constants::NON_IMPENDING);
        } 
        elseif ($admimssion->getBed()->getCurrentStatus() == Constants::NON_IMPENDING) {
                $admimssion->getBed()->setCurrentStatus(Constants::IMPENDING);
        }
        //////////////////////////////////
        $unit= $admimssion->getBed()->getRoom()->getUnit();
        $unit->setLastUpdated(new \DateTime('now'));
        $entityManager->persist($unit);
        $entityManager->flush();
        //////////////////////////////////
        $entityManager->persist($admimssion);
        $entityManager->flush();
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => true,
                // 'data' => $result
            ]);
        }
        $this->addFlash('error', 'Unable to discharge this patient! impending status changed');
        return $this->redirectToRoute('patient_show', ['id' => $admimssion->getPatient()->getId()]);
    }

    #[Route('/{id}/discharge', name: 'admimssion_discharge', methods: ['POST'])]
    public function discharge(Request $request, EntityManagerInterface $entityManager, Admimssion $admimssion, OutcomeRepository $outcomeRepository, ReferralsRepository $referralsRepository): Response
    {
        if ($request->get('outcome') == null) {
            $this->addFlash('error', 'Unable to discharge this patient! outcome field is required');
            return $this->redirectToRoute('patient_show', ['id' => $admimssion->getPatient()->getId()]);
        }
        $referal = null;
        if ($request->get('outcome') == 6) {
            if ($request->get('referal') == null) {
                $this->addFlash('error', 'Unable to discharge this patient! referal field is required');
                return $this->redirectToRoute('patient_show', ['id' => $admimssion->getPatient()->getId()]);
            } else {
                $referal = $referralsRepository->find($request->get('referal'));
            }
        }

        $outcome = $outcomeRepository->find($request->get('outcome'));
        $bed = $admimssion->getBed();
        $patient = $admimssion->getPatient();
        $bed->setAccessibility(Constants::FREE_BED);
        $bed->setCurrentStatus(Constants::NON_IMPENDING);
        $patient->setStatus(constants::REGISTERED);
        $entityManager->persist($patient);
        $entityManager->flush();
        //////////////////////////////////
        $unit= $admimssion->getBed()->getRoom()->getUnit();
        $unit->setLastUpdated(new \DateTime('now'));
        $entityManager->persist($unit);
        $entityManager->flush();
        ///////////////////////////////////////
        $entityManager->flush();
        $entityManager->persist($bed);
        $entityManager->flush();
        $admimssion->setDischargedAt(new \DateTime('now'));
        $admimssion->setStatus(Constants::ADMISSION_DISCHARGED);
        $admimssion->setOutcome($outcome);
        if ($referal != null)
            $admimssion->setReferOut($referal);
        $entityManager->persist($admimssion);
        $entityManager->flush();
        $this->addFlash('success', 'Patient discharged successfully');
        return $this->redirectToRoute('patient_show', ['id' => $admimssion->getPatient()->getId()]);
    }

    #[Route('/{id}', name: 'admimssion_show', methods: ['GET'])]
    public function show(Admimssion $admimssion): Response
    {
        return $this->render('admimssion/show.html.twig', [
            'admimssion' => $admimssion,
        ]);
    }

    #[Route('/{id}/edit', name: 'admimssion_edit', methods: ['POST'])]
    public function edit(Request $request, Patient $patient, EntityManagerInterface $entityManager, AdmimssionRepository $admimssionRepository, BedRepository $bedRepository): Response
    {
        $bedId = $request->get('bed');
        if ($bedId == null) {
            $this->addFlash('warning', 'You can\'t edit this admission! Please select new bed');
            return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
        }
        $allAdmissions = $admimssionRepository->getLastAdmission($patient)->getResult();
        $lastAdmission = count($allAdmissions) > 0 ? $allAdmissions[0] : null;
        if ($lastAdmission == null || $lastAdmission->getAdmission() != null) {
            $this->addFlash('warning', 'You can\'t re admit this patient!');
            return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
        }
        $lastAdmissionBed = $bedRepository->find($lastAdmission->getBed());
        $lastAdmissionBed->setAccessibility(Constants::FREE_BED);
        $newAdmissionBed = $bedRepository->find($bedId);
        $newAdmissionBed->setAccessibility(Constants::OCCUPIED_BED);
        $lastAdmission->setBed($newAdmissionBed);
        $entityManager->persist($lastAdmissionBed);
        $entityManager->flush();
        $entityManager->persist($newAdmissionBed);
        $entityManager->flush();
        $entityManager->persist($lastAdmission);
        $entityManager->flush();
        return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
    }

    #[Route('/{id}', name: 'admimssion_delete', methods: ['POST'])]
    public function delete(Request $request, Admimssion $admimssion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $admimssion->getId(), $request->request->get('_token'))) {
            if ($admimssion->getDischargedAt() == null) {
                $bed = $admimssion->getBed();
                $bed->setAccessibility(Constants::FREE_BED);
                $bed->setCurrentStatus(Constants::NON_IMPENDING);
                $entityManager->persist($bed);
                $entityManager->flush();
            }
            $patient = $admimssion->getPatient();
            $patient->setStatus(constants::REGISTERED);
            $entityManager->remove($admimssion);
            $entityManager->flush();
            return $this->json([
                'success' => true,
            ]);
        }
        return $this->json([
            'success' => false,
        ], 403);
    }
}
