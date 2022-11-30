<?php

namespace App\Controller;

use App\Entity\Encounter;
use App\Entity\Patient;
use App\Entity\Admimssion;
use App\Form\EncounterType;
use App\Repository\EncounterRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Patient as EntityPatientType;
use App\Repository\DepartmentRepository;
use App\Repository\PatientRepository;
use App\Repository\EncounterTypeRepository;
use App\Repository\ReasonOfReferralRepository;
use App\Repository\ReferralsRepository;
use App\Repository\DiseaseCategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/encounter')]
class EncounterController extends AbstractController
{
  #[Route('/', name: 'encounter_view', methods: ['GET'])]
public function view_encounters(ReasonOfReferralRepository $reasonOfReferralsRepository,ReferralsRepository $referralsRepository,EncounterRepository $encounterRepository,
Request $request, PaginatorInterface $paginator): Response
{
   
    $queryBuilder = $encounterRepository->getActiveEncounter();
    $data = $paginator->paginate($queryBuilder, 
      $request->query->getInt('page', 1),
        10
     );

return $this->render('encounter/active_ancounters.html.twig', [
    'encounters' => $data,
    'reason_of_referrals'=>$reasonOfReferralsRepository->findAll(),
    'referrals'=>$referralsRepository->findAll(),
   
    ]);
}

    #[Route('/{id}', name: 'app_encounter_index', methods: ['GET'])]
    public function index(ReasonOfReferralRepository $reasonOfReferralsRepository,ReferralsRepository $referralsRepository, EntityPatientType $entityPatientType,EncounterRepository $encounterRepository,Request $request, PaginatorInterface $paginator): Response
    {
       
        $queryBuilder = $encounterRepository->getEncounter($entityPatientType);
        $data = $paginator->paginate($queryBuilder, 
          $request->query->getInt('page', 1),
            1
         );

    return $this->render('encounter/index.html.twig', [
        'encounters' => $data,
        'patient' =>$entityPatientType,
        'reason_of_referrals'=>$reasonOfReferralsRepository->findAll(),
        'referrals'=>$referralsRepository->findAll()
    ]);
}


    #[Route('/{patient}/new/', name: 'app_encounter_new', methods: ['GET', 'POST'])]
    public function new(DiseaseCategoryRepository $diseaseCategoryRepository,Patient $patient, Request $request, EncounterTypeRepository $encounterTypeRepository,DepartmentRepository $departmentRepository ,EncounterRepository $encounterRepository): Response
    {
        $encounter = new Encounter();
        if ($request->getMethod() == 'POST') {
            $deptid = $request->get('dept');
            $typeid = $request->get('type');
            $diseaseid = $request->get('disease');
            $emergency = $request->get('emergency');
           
            $encounter->setCreatedAt(new DateTime());
            // $user = $this->security->getUser();
            $dept  = $departmentRepository->find($deptid);
            $disease  =$diseaseCategoryRepository->find($diseaseid);
            $type  = $encounterTypeRepository->find($typeid);
            $encounter->setDestination($dept);
            if(is_null($emergency)){
            $encounter->setEncounterType('Non-Emergency');
            }
            else{
            $encounter->setEncounterType($emergency);
        }
            $encounter->setType($type);
            $encounter->setDiseaseCategory($disease);
            $encounter->setCreatedBy($this->getUser());
            $encounter->setPatient($patient);
            $encounter->setReferout(null);
            $encounter->setReferralReason(null);
            $encounter->setSpecifyReason(null);
            $encounter->setReferredAt(null);
            $encounterRepository->add($encounter);
          
            $this->addFlash('success', 'Patient has been checked in successfully!');
            return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
       }

        return $this->redirectToRoute('patient_show', [
             
             'id' => $patient->getId(),
             'encounter' => $encounter,
           // 'form' => $form
        ]);

    }


    #[Route('/{id}/referout/', name: 'referr_out', methods: ['GET', 'POST'])]
    public function referr_out(EntityManagerInterface $entityManager,Encounter $encounter, PatientRepository $patientRepository,ReferralsRepository $referralsRepository,ReasonOfReferralRepository $reasonOfReferralsRepository, Request $request, ReasonOfReferralRepository $reasonOfReferralRepository, EncounterTypeRepository $encounterTypeRepository,DepartmentRepository $departmentRepository ,EncounterRepository $encounterRepository): Response
    {

          if ($request->getMethod() == 'POST') {

              $encounter->setReferralReason($reasonOfReferralRepository->find($request->get('reason')));
            $encounter->setReferout($referralsRepository->find($request->get('healthFacility')));
             $encounter->setSpecifyReason($request->get('specify'));

            $encounter->setReferredAt(new DateTime());
            $encounter->setUpdatedBy($this->getUser());
            $encounter->setReferredBy($this->getUser());

            
            $encounter->setPatient($patientRepository->find($request->get('patient_id')));
            $encounter->setCreatedAt(new DateTime());
            $entityManager->persist($encounter);
            $entityManager->flush();
        
            $encounterRepository->add($encounter);

            $this->addFlash('success', 'Patient has been referred out successfully!');
            return $this->redirectToRoute('patient_show', ['id' => $encounter->getPatient()->getId()]);
    }

        return $this->redirectToRoute('patient_show', [
             
             'id' =>$encounter->getPatient()->getId(),
             'encounter' => $encounter,
             'reason_of_referrals'=>$reasonOfReferralsRepository->findAll()
           // 'form' => $form
        ]);

    }

    #[Route('/{id}', name: 'app_encounter_show', methods: ['GET'])]
    public function show(Encounter $encounter): Response
    {
        return $this->render('encounter/show.html.twig', [
            'encounter' => $encounter,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_encounter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Encounter $encounter, EncounterRepository $encounterRepository): Response
    {

     
        $form = $this->createForm(EncounterType::class, $encounter);
     
      //  $form = $this->createForm(EncounterType::class, $encounter,['action'=>$this->generateUrl('app_encounter_edit',['id'=>$patient->getId()])]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $patient = $encounter->getPatient();
           // dd($patient);
            $encounter->setCreatedAt(new DateTime());
            $encounter->setUpdatedBy($this->getUser());

            $encounterRepository->add($encounter);
           # return $this->redirectToRoute('app_encounter_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
        }

        return $this->renderForm('encounter/edit.html.twig', [
            'encounter' => $encounter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_encounter_delete', methods: ['POST'])]
    public function delete(Request $request, Encounter $encounter, EncounterRepository $encounterRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$encounter->getId(), $request->request->get('_token'))) {
            $encounterRepository->remove($encounter);
        }

        return $this->redirectToRoute('app_encounter_index', [], Response::HTTP_SEE_OTHER);
    }
}
