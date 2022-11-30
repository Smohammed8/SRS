<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PatientRepository;
use App\Repository\AdmimssionRepository;
use App\Repository\AppointmentRepository;
use App\Repository\OutcomeRepository;
use App\Repository\WardRepository;
use App\Repository\UnitRepository;
use App\Repository\RoomRepository;
use App\Repository\BedRepository;
use App\Repository\UserRepository;


class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(UserRepository $userRepository,BedRepository $bedRepository,RoomRepository $roomRepository,UnitRepository $unitRepository, WardRepository $wardRepository, PatientRepository $patientRepository,AdmimssionRepository $admimssionRepository): Response
    {

          // $this->denyAccessUnlessGranted('vw_dshbrd'); 
          // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_ANONYMOUSLY'); 

        return $this->render('dashboard/index.html.twig', [
            ############  Dashbaord mislenouse counts per wards ########################
            'total_patinets'   =>$patientRepository->total_patients(),
            'total_admimssions'   =>$admimssionRepository->total_admimssions(),
            'new_admimssions'   =>$admimssionRepository->new_admimssions(),
            'total_wards'       =>$wardRepository->total_wards(),
            'total_units'      =>$unitRepository->total_units(),
            'total_rooms'       =>$roomRepository->total_rooms(),
            'total_beds'       =>$bedRepository->total_beds(),
            'free_beds'        =>$bedRepository->free_beds(),
            'total_users'       =>$userRepository->total_users(),
                ############  Dashbaord outcome counts per wards ########################
            'total_deaths'      =>$admimssionRepository->total_death(),
            'total_transfers'   =>$admimssionRepository->total_transfer(),
            'total_improves'    =>$admimssionRepository->total_improves(),
            'total_absoconds'    =>$admimssionRepository->total_abscond(),
            'total_referrals'   =>$admimssionRepository->total_referral(),
            'total_nochangs'    =>$admimssionRepository->total_nochange(),

            'total_self_discharges'    =>$admimssionRepository->total_self_dischage(),

              ############  Dashbaord unitcounts per wards ########################
            'units_in_medical_ward'       =>$unitRepository->units_in_medical_ward(),
            'units_in_surgical_ward'      =>$unitRepository->units_in_surgical_ward(),
            'units_in_pediatrics_ward'    =>$unitRepository->units_in_pediatrics_ward(),
            'units_in_mathernity_ward'    =>$unitRepository->units_in_mathernity_ward(),
            'units_in_gyne_ward'          =>$unitRepository->units_in_gyne_ward(),
            'units_in_orthopedics_ward'   =>$unitRepository->units_in_orthopedics_ward(),
            'units_in_nicu_ward'          =>$unitRepository->units_in_nicu_ward(),
            'units_in_ophtha_ward'        =>$unitRepository->units_in_ophtha_ward(),
            'units_in_psychiatric_ward'   =>$unitRepository->units_in_psychiatric_ward(),

          

          ############  Dashbaord bed counts per wards ########################
            'beds_in_medical_ward'       =>$bedRepository->beds_in_medical_ward(),
            'beds_in_surgical_ward'      =>$bedRepository->beds_in_surgical_ward(),
            'beds_in_pediatrics_ward'    =>$bedRepository->beds_in_pediatrics_ward(),
            'beds_in_mathernity_ward'    =>$bedRepository->beds_in_mathernity_ward(),
            'beds_in_gyne_ward'          =>$bedRepository->beds_in_gyne_ward(),


            'beds_in_orthopedics_ward'   =>$bedRepository->beds_in_orthopedics_ward(),
            'beds_in_nicu_ward'          =>$bedRepository->beds_in_nicu_ward(),
            'beds_in_ophtha_ward'        =>$bedRepository->beds_in_ophtha_ward(),
            'beds_in_psychiatric_ward'   =>$bedRepository->beds_in_psychiatric_ward(),

           
       
        ]);
    }
}

