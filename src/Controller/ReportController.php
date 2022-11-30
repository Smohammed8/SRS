<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Appointment;
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
Use App\Repository\AdmimssionTypeRepository;
use Andegna\DateTimeInterface;
use Andegna\DateTime;
use Andegna\DateTimeFactory;
//use DateTime;

use App\Helper\Constants;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Helper\DomPrint;
use App\Repository\AppointmentRepository;
use App\Repository\AdmimssionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Ods;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;


class ReportController extends AbstractController {
    

    #[Route('/general_report', name: 'general_report')]
    public function getGeneralReport(BedRepository $bedRepository, AdmimssionRepository $admimssionRepository,Request $request): Response
    {
 
     if ($request->getMethod() == 'POST') {
        $sdate  = $request->get('sdate');
        $edate = $request->get('edate');
        $sd          = explode('/',$sdate);
        $ed          = explode('/',$edate);
        $start       = DateTimeFactory::of($sd[2], $sd[1], $sd[0]);
        $end         = DateTimeFactory::of($ed[2], $ed[1], $ed[0]);

            if ($end->toGregorian() <  $start->toGregorian()) {

            $this->addFlash('danger', 'Start date must be before from the end date!');
            return $this->redirectToRoute('general_report', [], Response::HTTP_SEE_OTHER);
        
             }
            
              $data =   $admimssionRepository->getReferrals($start->toGregorian(), $end->toGregorian());
              $pdfOptions = new Options();
              $pdfOptions->setIsRemoteEnabled(true);
              $pdfOptions->set('defaultFont', 'Arial');
              $dompdf = new Dompdf($pdfOptions);
              $dompdf->setOptions($pdfOptions);
              $dompdf->output();
              $pdfOptions->set('isRemoteEnabled', TRUE);
              $html = $this->renderView('report/general_print.html.twig', [

                'total_admimssions'        =>$admimssionRepository->totalAdmimssions($start->toGregorian(),$end->toGregorian()),
                'total_emergency'          =>$admimssionRepository->total_emergency($start->toGregorian(),$end->toGregorian()),
                'total_non_emergency'      =>$admimssionRepository->non_emergency($start->toGregorian(),$end->toGregorian()),
                'total_beds'               =>$admimssionRepository->totalBeds($start->toGregorian(),$end->toGregorian()),
                'total_deaths'            =>$admimssionRepository->total_deaths($start->toGregorian(),$end->toGregorian()),
                'length_stay_in_days'      =>$admimssionRepository->lengthStay($start->toGregorian(),$end->toGregorian()),
                'total_discharges'         =>$admimssionRepository->total_discharges($start->toGregorian(),$end->toGregorian()),
                // 'surgical_wait_list_in_days' =>$admimssionRepository->wait_list(),
                // 'total_patient_elective_surgery'     =>$admimssionRepository->total_patient_elective_surgery(),
                // 'average_days_surgical_wait_list_to_total_patient_elective_surgery' =>$admimssionRepository->average(),
               
                'admimssions' => $data,
                'start_date' => $sdate,
                'end_date' => $edate,
                'user'  => $this->getUser()
              ]);
                   $dompdf->loadHtml($html);
                   $dompdf->setPaper('A4', 'portrait'); //landscape
                   $dompdf->render();
                   ob_get_clean();
            
              $dompdf->stream("general report.pdf", [
              "Attachment" => false  // view in browser
            
            ]);
        }
             return $this->render('report/general_report.html.twig', [
            'controller_name' => 'General report',

            //'form' => $form->createView()
        ]);
    }
    #[Route('/appointment_report', name: 'appointment_report', methods: ['GET'])]
    public function getAppointment(AppointmentRepository $appointmentRepository,Request $request): Response
    {

        $form = $this->createFormBuilder()
        ->setMethod('GET')
            // ->add('type', ChoiceType::class, array('choices'  => array('............' => '',  'Generate as PDF' => 1,  'Export as Excel' => '2')));
       
            ->add('unit', EntityType::class, [
                'class' => Unit::class,
                'required' =>false,
                'query_builder' => function (EntityRepository $er) {
                    $res = $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                    return $res;
                },

                'placeholder' => " All units...",
            ]);
            $form = $form->getForm();
             $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {


            $sdate  = $request->get('sdate');
            $edate = $request->get('edate');
            $unit   = $form->get('unit')->getData();
             
            $sd          = explode('/',$sdate);
            $ed          = explode('/',$edate);
            $start       = DateTimeFactory::of($sd[2], $sd[1], $sd[0]);
            $end         = DateTimeFactory::of($ed[2], $ed[1], $ed[0]);

                    
            if ($end->toGregorian() <  $start->toGregorian()) {

                $this->addFlash('warning', 'Start date must be before from the end date!');
                return $this->redirectToRoute('appointment_report', [], Response::HTTP_SEE_OTHER);
            
                  }
           
             $spreadsheet = new Spreadsheet();
            
            foreach (range('A', 'M') as $columnID) {

                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            } // end of for loop range

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Patient Name');
            $sheet->setCellValue('B1', 'Card No');
            $sheet->setCellValue('C1', 'Age');
            $sheet->setCellValue('D1', 'Sex');
            $sheet->setCellValue('E1', 'Address');
            $sheet->setCellValue('F1', 'Date of Admission');
            $sheet->setCellValue('G1', 'Daignosis');
            $sheet->setCellValue('H1', 'Date of Appointment');
            $sheet->setCellValue('I1', 'Phone');
            $sheet->setCellValue('J1', 'Ward');
            $sheet->setCellValue('K1', 'Patient Level');
            $sheet->setCellValue('L1', 'Nurse');
            $sheet->setCellValue('M1', 'Condition');

              if ($unit ===null) {
                $data = $appointmentRepository->getReport($unit, $start->toGregorian(),$end->toGregorian());  
            
              } // the end of if
              else{
              $data = $appointmentRepository->getReport($unit, $start->toGregorian(),$end->toGregorian());  
              }
                 $totalResult = $data;
                    $x = 2;
                    $soh = 0;

            foreach ($totalResult as $result) {
                $sheet->setCellValue('A' . $x, $result->getPatient());
                $sheet->setCellValue('B' . $x, $result->getPatient()->getMRN());
                $sheet->setCellValue('C' . $x, "25");
                $sheet->setCellValue('D' . $x, $result->getPatient()->getGender());
                $sheet->setCellValue('E' . $x, $result->getPatient()->getAddress());
                $sheet->setCellValue('F' . $x, $result?->getAppointAt());
                $sheet->setCellValue('G' . $x, $result->getReason());
                $sheet->setCellValue('H' . $x, $result->getAppointedAt());  
                $sheet->setCellValue('I' . $x, $result->getPatient()?->getPhone());  
                $sheet->setCellValue('J' . $x, $result->getUnit()?->getWard());  
                $sheet->setCellValue('K' . $x, $result->getPriorityLevel()); 
                $sheet->setCellValue('L' . $x, $result->getUser()); 
                $sheet->setCellValue('M' . $x, $result->getReason());   

                $x++;
              } // the end of for loop of total result
                $writer = new Xlsx($spreadsheet);
                $fileName = "Patient appointment report" . '.xlsx';
                $temp_file = tempnam(sys_get_temp_dir(), $fileName);
                $writer->save($temp_file);
                return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
            }
             // the end of submit
    
                return $this->render('report/appointment_report.html.twig', [
                'form' => $form->createView()
                ]);
    }  // the end of function brace

    public function getAge($dob,$visit){

          $interval =$dob->diff($visit);
           $year = $interval->format('%y');
           return $year; 
        }

    #[Route('/admimssion_report', name: 'admimssion_report', methods: ['GET'])]
    public function getAddmissions(AdmimssionRepository $admimssionRepository ,Request $request): Response
    {

        $form = $this->createFormBuilder()
        ->setMethod('GET')
        ->add('outcome', EntityType::class, [
            'class' => Outcome::class,
            'required' =>false,
            'query_builder' => function (EntityRepository $er) {
                $res = $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                return $res;
            },

            'placeholder' => " All outcome...",
        ]);
             $form = $form->getForm();
             $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
            $sdate  = $request->get('sdate');
            $edate = $request->get('edate');
           // $outcome  = $form->get('outcome')->getData()->getId();
            $outcome  = $form->get('outcome')->getData();
            $sd          = explode('/',$sdate);
            $ed          = explode('/',$edate);
            $start       = DateTimeFactory::of($sd[2], $sd[1], $sd[0]);
            $end         = DateTimeFactory::of($ed[2], $ed[1], $ed[0]);

            if ($end->toGregorian() <  $start->toGregorian()) {

                $this->addFlash('danger', 'Start date must be before from the end date!');
                return $this->redirectToRoute('admimssion_report', [], Response::HTTP_SEE_OTHER);
            
                  }
           
             $spreadsheet = new Spreadsheet();
            
            foreach (range('A', 'N') as $columnID) {

                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            } // end of for loop range

            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Patient Name');
            $sheet->setCellValue('B1', 'Card No');
            $sheet->setCellValue('C1', 'Age');
            $sheet->setCellValue('D1', 'Sex');
            $sheet->setCellValue('E1', 'Address');
            $sheet->setCellValue('F1', 'Date of Admimssion');
            $sheet->setCellValue('G1', 'Date of Discharge');
            $sheet->setCellValue('H1', 'Duration');
            $sheet->setCellValue('I1', 'Bed');
            $sheet->setCellValue('J1', 'Outcome');
            $sheet->setCellValue('K1', 'Nurse');
            $sheet->setCellValue('L1', 'Re-admimitted');
            $sheet->setCellValue('M1', 'Admission Type');

             if ($outcome===null) {
                $data = $admimssionRepository->getAdmissionReport($start->toGregorian(),$end->toGregorian());  
              } 
              else{
               $data = $admimssionRepository->getReport($outcome,$start->toGregorian(),$end->toGregorian());  
              }    
               $totalResult = $data;
                    $x = 2;
                    $soh = 0;
                foreach ($totalResult as $result) {
                $sheet->setCellValue('A' . $x, $result->getPatient());
                $sheet->setCellValue('B' . $x, $result->getPatient()?->getMRN());
                $age2 = $this->getAge($result->getCreatedAt(),$result->getPatient()->getDob());
                $sheet->setCellValue('C' . $x, $age2);
                $sheet->setCellValue('D' . $x, $result->getPatient()?->getGender());
                $sheet->setCellValue('E' . $x, $result->getPatient()?->getAddress());
                $sheet->setCellValue('F' . $x, $result->getCreatedAt()); 
                $sheet->setCellValue('G' . $x, $result->getDischargedAt());
                $sheet->setCellValue('H' . $x, $result->getDuration());
                $sheet->setCellValue('I' . $x, $result->getBed());  
                $sheet->setCellValue('J' . $x, $result->getOutcome()); 
                $sheet->setCellValue('K' . $x, $result->getUser()); 
                 if($result->getAdmission()==''){
                 $sheet->setCellValue('L' . $x, "-"); 
                  }
                 else {
                 $sheet->setCellValue('L' . $x, "Yes"); 
                  }
                $sheet->setCellValue('M' . $x, $result->getType());   
                $x++;
              } // the end of for loop of total result
                $writer = new Xlsx($spreadsheet);
                $fileName = "Patient admission report" . '.xlsx';
                $temp_file = tempnam(sys_get_temp_dir(), $fileName);
                $writer->save($temp_file);
                return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
            }
             // the end of submit
    
                return $this->render('report/admimssion_report.html.twig', [
                'form' => $form->createView()
                ]);
    }           // the end of function brace




    #[Route('/referral-report_pdf', name: 'referral_report')]
    public function getReferralReport(DomPrint $domPrint, AdmimssionRepository $admimssionRepository, Request $request)
    { 


        if ($request->getMethod() == 'POST') {
        
            $sdate  = $request->get('sdate');
            $edate = $request->get('edate');
            $sd          = explode('/',$sdate);
            $ed          = explode('/',$edate);
            $start       = DateTimeFactory::of($sd[2], $sd[1], $sd[0]);
            $end         = DateTimeFactory::of($ed[2], $ed[1], $ed[0]);

                if ($end->toGregorian() <  $start->toGregorian()) {

                $this->addFlash('danger', 'Start date must be before from the end date!');
                return $this->redirectToRoute('admimssion_report', [], Response::HTTP_SEE_OTHER);
            
              }
               
                  
                  $pdfOptions = new Options();
                  $pdfOptions->setIsRemoteEnabled(true);
                  $pdfOptions->set('defaultFont', 'Arial');
                  $dompdf = new Dompdf($pdfOptions);
                  $dompdf->setOptions($pdfOptions);
                  $dompdf->output();
                  $pdfOptions->set('isRemoteEnabled', TRUE);
                  
                  $html =$this->renderView('report/print.html.twig', [
                'total_admimssions'      =>$admimssionRepository->totalAdmimssions($start->toGregorian(),$end->toGregorian()),
                'total_emergency'        =>$admimssionRepository->total_emergency($start->toGregorian(),$end->toGregorian()),
                'start_date' => $sdate,
                'end_date' => $edate,
                'user'  => $this->getUser()
                  ]);
                      $dompdf->loadHtml($html);
                      $dompdf->setPaper('A4', 'portrait'); //landscape
                      $dompdf->render();
                       ob_get_clean();
                
                  $dompdf->stream(" Referar report.pdf", [
                  "Attachment" => false  // view in browser
                
                ]);
                }
      
                  return $this->render('report/referral_report.html.twig', [
                  
                  ]);
        
        }

}  // the end of class brace
    
    
