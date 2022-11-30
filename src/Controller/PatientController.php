<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use App\Repository\EncounterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use \Twig_Extension;
use App\Entity\User;
use App\Entity\Visitor;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
// use Andegna\DateTime;
use DateTime;
use Andegna\DateTimeFactory;
use Andegna\DateTime as et_date;
use Andegna\DateTimeInterface;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Admimssion;
use App\Repository\AdmimssionRepository;
use App\Entity\Appointment as EntityAppointmentType;
use App\Entity\Encounter;
use App\Entity\BedPrice;
use App\Entity\Visitor as EntityVisitorType;
use App\Helper\Constants;
use App\Helper\DomPrint;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\AdmimssionTypeRepository;
use App\Repository\AppointmentRepository;
use App\Repository\AssessmentRepository;
use App\Repository\ReasonOfReferralRepository;
use App\Repository\DepartmentRepository;
Use App\Repository\EncounterTypeRepository;
use App\Repository\OutcomeRepository;
use App\Repository\ReferralsRepository;
use App\Repository\VisitorRepository;
use App\Repository\DiseaseCategoryRepository;
use App\Repository\BedPriceRepository;
use Proxies\__CG__\App\Entity\Appointment;

#[Route('/patient')]
class PatientController extends AbstractController
{
    #[Route('/', name: 'patient_index', methods: ['GET'])]
    public function index(PatientRepository $patientRepository, Request $request, PaginatorInterface $paginator): Response
    {

        //  $this->denyAccessUnlessGranted('vw_amb');
        /* if ($patient->getStatus() === Constants::ADMITTED) 
            $patient->setStatus(Constants::ADMITTED); // */

            $status =[

                "Waiting"=> '1',
                "Admitted"=> '2',
                'Discharged' => '3',
                'Heigher Referral' => '4',
             
              
            ];

        $form = $this->createFormBuilder()
            ->setMethod('Get')

            ->add('status', ChoiceType::class,[
                "choices" =>$status ,
                'mapped'=>false,
                'multiple'=>false,
                "placeholder"=>"Select Status..."])

            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $start_date  = $request->get('sdate');
            $end_date   = $request->get('edate');
            //$status   = $request->get('status');
            $status   = $form->get('status')->getData();

            $sd          = explode('/', $start_date);
            $ed          = explode('/', $end_date);

            $sdate         = DateTimeFactory::of($sd[2], $sd[1], $sd[0]);
            $edate         = DateTimeFactory::of($ed[2], $ed[1], $ed[0]);

            $sd = $sdate->toGregorian();
            $ed = $edate->toGregorian();

            if ($sdate->toGregorian() > $edate->toGregorian()) {

                $this->addFlash('warning', 'Start date must be before from the end date!');
                return $this->redirectToRoute('patient_index', [], Response::HTTP_SEE_OTHER);
            }

            $queryBuilder = $patientRepository->getFilter($sdate->toGregorian(), $edate->toGregorian(),$status);
        } 
        else {
          
            $queryBuilder = $patientRepository->getQuery($request->query->get('search'));
        }
        $data = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('patient/index.html.twig', [
            'patients' => $data,
            'form' => $form,
            'total_patinets'   =>$patientRepository->total_patients(),
            'form' => $form->createView(),
            // 'filterform' => $filterform->createView(),
        ]);
    }
/////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/batch_upload', name: 'batch_upload', methods: ['GET', 'POST'])]
    public function  batch_upload(Request $request): Response
    {
       
        $patient = new Patient();
        $form = $this->createFormBuilder()
        ->setMethod('POST')
        ->add('file', filetype:: class,[

          'required' => true
        ])

        ->getForm();
      $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dob  = $request->get('dob'); // age given from user
         /////////////////////////////////////////////////////////////////
            $sd   = explode('/', $dob);
            $date_of_birth  = DateTimeFactory::of($sd[2], $sd[1], $sd[0]);

            if ($date_of_birth->toGregorian() >= new \DateTimeImmutable()) {
                $this->addFlash('warning', 'Invalid selections!');
                return $this->redirectToRoute('patient_index', [], Response::HTTP_SEE_OTHER);
            }
         ///////////////////////////////////////////////////////////////////////////////


            else {
               
                $patient->setCreatedAt(new \DateTimeImmutable());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($patient);
                $entityManager->flush();
                $this->addFlash('success', "Successfully uploaded!");
                return $this->redirectToRoute('patient_index', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->renderForm('patient/upload.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }
//////////////////////////////////////////////////////////////////////////////

    #[Route('/new', name: 'patient_new', methods: ['GET', 'POST'])]
    public function new(Request $request,PatientRepository $patientRepository): Response
    {
       
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            $mrn = $form->get('MRN')->getData(); // to get specific form value card number
            $phone = $form->get('phone')->getData(); // to get specific form value for phone

            $isExist  =  $patientRepository->checkIfExist($mrn,$phone);
         
            if ($isExist > 0) {
                $this->addFlash('danger', "Sorry this patient already exist! Check card number or phone!");

                return $this->redirectToRoute('patient_new', [], Response::HTTP_SEE_OTHER);
            
            }

           // $dob  = $request->get('dob');
            $age  = $request->get('age');
            // $sd   = explode('/', $dob);

            // $date_of_birth  = DateTimeFactory::of($sd[2], $sd[1], $sd[0]);
            // if ($date_of_birth->toGregorian() >= new \DateTimeImmutable()) {
            //     $this->addFlash('warning', 'Invalid date pf birth selections!');
            //     return $this->redirectToRoute('patient_index', [], Response::HTTP_SEE_OTHER);
            // } 
          
            $cdate = date('Y-m-d');
            $age_input = date('Y-m-d',strtotime($cdate.'-'.$age.'years'));
            $dob = DateTime::createFromFormat('Y-m-d', $age_input);
      
         //  dd($newDate );
                if ($age <=0 || $age > 100 ) {
                    $this->addFlash('warning', 'Invalid age, It should be less than 100 and greater than 0 !');
                    return $this->redirectToRoute('patient_index', [], Response::HTTP_SEE_OTHER);
                }
           
                else {
              //  $patient->setDob($date_of_birth->toGregorian());
                $patient->setDob($dob);
                $patient->setUser($this->getUser());
                $patient->setStatus(constants::REGISTERED);
                $patient->setCreatedAt(new \DateTimeImmutable());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($patient);
                $entityManager->flush();
                $this->addFlash('success', "The patient has been successfully registered!");
                return $this->redirectToRoute('patient_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->renderForm('patient/new.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

///////////////////////////////////////////////////////////////////////	
#[Route('/check_mrn', name: 'check_mrn', methods: ['POST'])]
 public function checkMrn(PatientRepository $patientRepository, Request $request)
 {
    if ($request->isXmlHttpRequest()) {
       if ($request->getMethod() == 'POST'){
        $mrn = $request->get('MRN');
       
    }
}
  $result = $patientRepository->checkpatient($mrn);
    if($result)
    {
    echo  1;	
    }
    else
    {
    echo  0;	
    }
}




    #[Route('/search', name: 'search_result', methods: ['POST'])]
    public function searchResult(PatientRepository $patientRepository, PaginatorInterface $paginator, Request $request): Response
    {

     //  dd($this->isGranted("ROLE_GATE_KEEPER"));

        if ($request->getMethod() === 'POST') {
            $searchKey = $request->request->get('search');
            if ($searchKey) {

              
            
               $data = $patientRepository->searchResult($searchKey);
                if (count($data) < 1) {
                       if ($this->isGranted("ROLE_NURSE") || $this->isGranted("ROLE_PHYSICIAN ") || $this->isGranted("ROLE_CHECKPOINT") || $this->isGranted("ROLE_ADMIN") || $this->isGranted("ROLE_SUPERADMIN") || $this->isGranted("ROLE_LIAISON")  || $this->isGranted("ROLE_LIAISON")  )  {
                        return $this->render('patient/result_show.html.twig', [
                        'message' => 'does not exist!',
                        'here' => 'http://10.142.65.11/patient/new',
                        'searchkey' => $searchKey
                         ]);
                       }
                     else{
                        return $this->render('patient/result_patient.html.twig', [
                            'message' => 'does not exist!',
                            'searchkey' => $searchKey
                           ]);
                       }
                    }
                $patient = $data[0];
                if ($data != null) {
                return $this->redirectToRoute('patient_show', ['id' => $patient->getId()]);
                    
                }
            }

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }
    }


    #[Route('/{id}', name: 'patient_show', methods: ['GET'])]
    public function show(VisitorRepository $visitorRepository,
    EncounterTypeRepository  $encounterTypeRepository,
    DepartmentRepository $departmentRepository,
    EncounterRepository $encounterRepository,
    DiseaseCategoryRepository $diseaseCategoryRepository,
    AppointmentRepository  $appointmentRepository,
    ReasonOfReferralRepository $reasonOfReferralsRepository,
    Patient $patient, 
    AdmimssionRepository $admimssionRepository, 
    BedPriceRepository $bedPriceReppository,
    AssessmentRepository   $assessmentRepository,
    AdmimssionTypeRepository $admimssionTypeRepository, 
    Request $request, PaginatorInterface $paginator,OutcomeRepository $outcomeRepository,ReferralsRepository $referralsRepository): Response
    {
       // $queryBuilder = $admimssionRepository->getQuery($request->query->get('search'));
        
      //  $data = $paginator->paginate( $queryBuilder, $request->query->getInt('page', 1),
        //     1
        // );
        $types = $admimssionTypeRepository->findAll();
        $allAdmissions = $admimssionRepository->getLastAdmission($patient)->getResult();
        $lastAdmission = count($allAdmissions) > 0 ? $allAdmissions[0] : null;

        $canAdmit = false;
        if ($lastAdmission == null) {
            $canAdmit = true;
        } else {
            // dd($lastAdmission->getDischargedAt());
            if ($lastAdmission->getDischargedAt() != null) {
                $canAdmit = true;
            }
        }
      
        $encounters   = $encounterRepository->getEncounter($patient);

       // $data=  $admimssionRepository->getAdmission($patient);
        $admimssions   = $paginator->paginate($admimssionRepository->getAdmission($patient), $request->query->getInt('page', 1),  2   );

   
        $appointments  =$appointmentRepository->getAppointment($patient);

        $outcomes = $outcomeRepository->findAll();
        $constant = new Constants();
        $referals = $referralsRepository->findAll();

         $queryBuilder  = $visitorRepository->getVistors();
         $visitors  = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         3
           );


 if ($this->isGranted("ROLE_NURSE") || $this->isGranted("ROLE_PHYSICIAN ") || $this->isGranted("ROLE_CHECKPOINT") || $this->isGranted("ROLE_ADMIN") || $this->isGranted("ROLE_SUPERADMIN") || $this->isGranted("ROLE_LIAISON")  || $this->isGranted("ROLE_LIAISON")  )  {

      
    return $this->render('patient/show.html.twig', [
        'patient' => $patient,
        'constant' => $constant,
        'canAdmit' => $canAdmit,
        'types' => $types,
        'outcomes' => $outcomes,
        'referals' => $referals,
        'referrals'     =>$referralsRepository->findAll(),
       
        'encounters'      =>$paginator->paginate($encounterRepository->getEncounter($patient), $request->query->getInt('page', 1),  1   ),
        'admimssions'      =>$paginator->paginate($admimssionRepository->getAdmission($patient), $request->query->getInt('page', 1),  1   ),
        'appointments'    =>$paginator->paginate($appointmentRepository->getAppointment($patient), $request->query->getInt('page', 1),  1   ),
            
        'encounter_types'  =>$encounterTypeRepository->findAll(),
        'departments'    =>$departmentRepository->findAll(),
        'reason_of_referrals'=>$reasonOfReferralsRepository->findAll(),
        'disease_categorys'=>$diseaseCategoryRepository->findAll(),
        'price' => $bedPriceReppository->getPrice(),
        'assessments'=>$assessmentRepository->getDiagnosis($patient)
          ]
        );
        }

    else if ($this->isGranted("ROLE_DATA_ENCODER"))  {

       // $queryBuilder = $encounterRepository->getQuery($request->query->get('search'));
      //  $enc = $paginator->paginate($encounterRepository->getEnounter($patient->getId()),  $request->query->getInt('page', 1),   1  );                
      return $this->render('patient/encounter_show.html.twig', [
        'patient'         => $patient,
       // 'encounters'      =>$enc,
        'referrals'       =>$referralsRepository->findAll(),
        'encounter_types' =>$encounterTypeRepository->findAll(),
        'departments'     =>$departmentRepository->findAll(),
        'encounters'      =>$paginator->paginate($encounterRepository->getEncounter($patient), $request->query->getInt('page', 1),  1   ),
        'admimssions'      =>$paginator->paginate($admimssionRepository->getAdmission($patient), $request->query->getInt('page', 1),  1   ),
        'appointments'    =>$paginator->paginate($appointmentRepository->getAppointment($patient), $request->query->getInt('page', 1),  1   ),
        'disease_categorys'=>$diseaseCategoryRepository->findAll(),
        'reason_of_referrals'=>$reasonOfReferralsRepository->findAll(),
        'assessments'=>$assessmentRepository->getDiagnosis($patient),
        'price' => $bedPriceReppository->getPrice()
          ]
        );
        }
    else  {
            return $this->render('patient/gatekeeper_show.html.twig', [
                'patient' => $patient,
                'referrals'     =>$referralsRepository->findAll(),
                'encounters'      =>$paginator->paginate($encounterRepository->getEncounter($patient), $request->query->getInt('page', 1),  1   ),
                'admimssions'      =>$paginator->paginate($admimssionRepository->getAdmission($patient), $request->query->getInt('page', 1),  1   ),
                'appointments'    =>$paginator->paginate($appointmentRepository->getAppointment($patient), $request->query->getInt('page', 1),  1   ),
                'lastAdmimssion' => $lastAdmission,
                'visitors'=>$visitors,
                'reason_of_referrals'=>$reasonOfReferralsRepository->findAll(),
                'disease_categorys'=>$diseaseCategoryRepository->findAll(),
                'assessments'=>$assessmentRepository->getDiagnosis($patient),
                'price' => $bedPriceReppository->getPrice()
    
            ]);
          }
    }

    #[Route('/{id}/edit', name: 'patient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Patient $patient): Response
    {
        $form = $this->createForm(PatientType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
               
              $age  = $request->get('age');
               $cdate = date('Y-m-d');
               $age_input = date('Y-m-d',strtotime($cdate.'-'.$age.'years'));
               $dob = DateTime::createFromFormat('Y-m-d', $age_input);
        
                   if ($age <=0 || $age > 100 ) {
                       $this->addFlash('warning', 'Invalid age, It should be less than 100 and greater than 0 !');
                       return $this->redirectToRoute('patient_index', [], Response::HTTP_SEE_OTHER);
                   }
              
                   else {
             $patient->setDob($dob);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('warning', "Successfully updated!");

            return $this->redirectToRoute('patient_index', [], Response::HTTP_SEE_OTHER);
        }
    }
        return $this->renderForm('patient/edit.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'patient_delete', methods: ['POST'])]
    public function delete(Request $request, Patient $patient): Response
    {
        if ($this->isCsrfTokenValid('delete' . $patient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($patient);
            $entityManager->flush();
            $this->addFlash('danger', "Successfully deleted!");
        }

        return $this->redirectToRoute('patient_index', [], Response::HTTP_SEE_OTHER);
    }
}
