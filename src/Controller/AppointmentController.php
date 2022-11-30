<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use App\Repository\UnitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Andegna\DateTime;
use Andegna\DateTimeFactory;
use Andegna\DateTime as et_date;
use Andegna\DateTimeInterface;

use App\Helper\Constants;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Helper\DomPrint;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Patient as EntityPatientType;

#[Route('/appointment')]
class AppointmentController extends AbstractController
{
    #[Route('/', name: 'appointment_index', methods: ['GET'])]
    public function index(UnitRepository $unitRepository,Request $request, PaginatorInterface $paginator,AppointmentRepository $appointmentRepository): Response
    {


       
        
        $queryBuilder =  $appointmentRepository->getAppointments($request->query->get('search'));
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         10
      );
    
        return $this->render('appointment/index.html.twig', [
            'appointments' => $data,
            'units'=>$unitRepository->findAll(),
            
        
        ]);
    }
       // functions used to return date interval in days
    public function timeDiff($visitDate,$appointedAt){

      $interval =$visitDate->diff($appointedAt);
         $days = $interval->format('%R%a');
         return $days; 
      }

    #[Route('/appointment_view/{id}', name: 'appointment_view', methods: ['GET'])]
    public function appointmentShow(UnitRepository $unitRepository,EntityPatientType $entityPatientType, Request $request, PaginatorInterface $paginator,AppointmentRepository $appointmentRepository): Response
    {

        //  $appointment = new Appointment();
        //  if($appointment->getAppointAt() < new \DateTimeImmutable()){
        //  $appointment->setStatus(Constants::CLOSED);
 
        //   }

        $queryBuilder =  $appointmentRepository->getPatientAppointments($entityPatientType,$request->query->get('search'));
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         10
      );

        return $this->render('appointment/index.html.twig', [
            'appointments' => $data,
            'patient' =>$entityPatientType,
            'units'=>$unitRepository->findAll()
        
        ]);
    }
    #[Route('/appointment_list/{id}', name: 'appointment_list', methods: ['GET'])]
    public function getAppointments(EntityPatientType $entityPatientType, Request $request, PaginatorInterface $paginator,AppointmentRepository $appointmentRepository): Response
    {

            //     $appointment = new Appointment();
            //     if($appointment->getAppointAt() < new \DateTimeImmutable()){
            //      $appointment->setStatus(Constants::CLOSED);
        
            //  }
        $queryBuilder =  $appointmentRepository->getPatientAppointments($entityPatientType,$request->query->get('search'));
        $data = $paginator->paginate(
         $queryBuilder,
         $request->query->getInt('page', 1),
         1
      );
        return $this->render('appointment/view_appointment.html.twig', [
            'appointments' => $data,
            'patient' =>$entityPatientType
        
        ]);
    }


 

    #[Route('/search_appointment', name: 'search_appointment', methods: ['POST'])]
    public function searchAppointment(UnitRepository $unitRepository,AppointmentRepository $appointmentRepository, PaginatorInterface $paginator, Request $request): Response
    {

    
        if ($request->getMethod() === 'POST') {
            
            $date = $request->request->get('date');
            $unit = $request->request->get('unit');
          
            if ($date and $unit) {
                    $sd          = explode('/', $date);
                    $sdate         = DateTimeFactory::of($sd[2], $sd[1], $sd[0]);
                    $user_date = $sdate->toGregorian();
            
                  $queryBuilder =$appointmentRepository->filterAppointments($unit,$user_date);
            
                $data = $paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page', 1),
                10
            );
        }
    
        return $this->render('appointment/index.html.twig', [
            'appointments' => $data,
            'units'=>$unitRepository->findAll()
          
        
        ]);
        }
    }
// this will return "true" if its sunday or saturday or false it its not a weekend
function is_weekend($appointed_date){

    $datetime = new DateTime($appointed_date);
    $dt2 = $datetime->format('l');
	$dt3 = strtolower($dt2);

      // dd($dt3 );
	if(($dt3 === "ቅዳሜ" ) || ($dt3 === "እሑድ")){

		return true;
	}
    else
    {
	   return false;
	}
}
    #[Route('/new/{id}', name: 'appointment_new', methods: ['GET', 'POST'])]
    public function new( EntityPatientType $entityPatientType, AppointmentRepository $appointmentRepository,  Request $request, EntityManagerInterface $entityManager): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
         $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
           // $patient = $request->get('patient_id');
            $appointedAt  = $request->get('appointedAt');
            $shift = $form->get('shift')->getData();
        
            $sd   = explode('/',$appointedAt);
            $appointed = DateTimeFactory::of($sd[2], $sd[1], $sd[0]);
           // dd($entityPatientType);

       $appoints = $appointmentRepository->getAppoint($appointed->toGregorian(),$entityPatientType);
       if($appoints > 0 ){
   
           $this->addFlash('warning','No duplicated appointment allowed on single date!');

           return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);
         }

        $appointments = $appointmentRepository->getAppointmentsLimit($appointed->toGregorian(),$shift);
        if($appointments >= 3 ){
    
            $this->addFlash('warning','Appointment more than 30 per shift is not permit in this date and shift!');

            return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);
          }

       ///////////////////////////// check if appointment date is not on weekend ////////////////////
            if($this->is_weekend($appointed->toGregorian())){

                $this->addFlash('warning','Appointment is not permitted  on weekend!');

                return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);
              }
         if($appointed->toGregorian() <= new \DateTimeImmutable()){  
                $this->addFlash('warning','Invalid selections!');
                return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);
                 }

             else{
            $entityManager->persist($appointment);
            $appointment->setPatient($entityPatientType);
            $entityPatientType->setStatus(constants::WAITING);
            $appointment->setAppointAt(new \DateTimeImmutable()); // this is date visiting
            $appointment->setAppointedAt($appointed->toGregorian()); //this date of comming
            $appointment->setUser($this->getUser());
            $appointment->setTotalDays($this->timeDiff(new \DateTimeImmutable(),$appointed->toGregorian()));
            $appointment->setStatus(Constants::OPEN);
            $entityManager->flush();
            $this->addFlash('success', 'Tha patients appointment has been  successfully created!');
                   
            return $this->redirectToRoute('patient_show',["id"=>$entityPatientType->getId()]);

        }
    }
        return $this->renderForm('appointment/new.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
            'patient' =>$entityPatientType
        ]);
    }

    #[Route('/{id}', name: 'appointment_show', methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/{id}/edit', name: 'appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {

        $patient =$appointment->getPatient();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointment->setPatient($$patient);
            $appointment->setAppointAt(new \DateTimeImmutable()); // this is date visiting
            $appointment->setAppointedAt($appointed->toGregorian()); //this date of comming
            $appointment->setUser($this->getUser());
            $appointment->setStatus(Constants::OPEN);

            $entityManager->flush();

            return $this->redirectToRoute('patient_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form,
            'patient'=> $patient
        ]);
    }




    #[Route('/appointment-print_pdf/{id}', name: 'print_appointment', methods: ['GET'])]
    public function appointmentReport(DomPrint $domPrint, Request $request,Appointment $appointment) {
      {
        if ($appointment->getStatus() === Constants::OPEN)
            
             $domPrint->print('appointment/appointment_pdf.html.twig', [
                 'appointment' => $appointment], "Appointment",
                 
             DomPrint::ORIENTATION_PORTRAIT, DomPrint::PAPER_A5, true);
           
      
            else {

            $this->addFlash('danger', 'Appointment closed');
            return $this->redirect($request->headers->get('referer'));
        }
    }
}




    // #[Route('/appointment-print_pdf/{id}', name: 'print_appointment', methods: ['GET'])]
    // public function appointmentReport(Request $request,Appointment $appointment)
    // {
    //      // Configure Dompdf according to your needs
    //     $pdfOptions = new Options();
    //     $pdfOptions->setIsRemoteEnabled(true);
    //     $pdfOptions->set('defaultFont', 'Arial');
    //     $pdfOptions->set('fontDir', $this->parameter->get('kernel.project_dir') . "/fonts");

     
    //     // Instantiate Dompdf with our options
    //     $dompdf = new Dompdf($pdfOptions);
    //     $dompdf->setOptions($pdfOptions);
    //     $dompdf->output();

    //     $pdfOptions->set('isRemoteEnabled', TRUE);
    //    // $pdfOptions->set('tempDir', '/home2/directory/public_html/directory/pdf-export/tmp');
    //     // Retrieve the HTML generated in our twig file
    //     $html = $this->renderView('appointment/appointment_pdf.html.twig', [
    //          'appointment' => $appointment,
    //           //'patient'=>$patient
               
    //     ]);
      
    //     // Load HTML to Dompdf
    //     $dompdf->loadHtml($html);
        
    //     // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
    //     $dompdf->setPaper('A5', 'portrait'); //landscape

    //     // Render the HTML as PDF
    //     $dompdf->render();
    //     ob_get_clean();

    //     // Output the generated PDF to Browser (inline view)
    //     $dompdf->stream("appointment.pdf", [
    //         "Attachment" => false  // view in browser
    //         //"Attachment" => true  // forec download
    //     ]);
    // }


    #[Route('/{id}', name: 'appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('appointment_index', [], Response::HTTP_SEE_OTHER);
    }
}
