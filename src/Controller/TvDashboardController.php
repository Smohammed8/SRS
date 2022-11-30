<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Ward;
use App\Repository\WardRepository;
use App\Repository\UserRepository;
use App\Repository\UnitRepository;
use App\Repository\RoomRepository;
use App\Repository\BedRepository;
use App\Repository\AdminssionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TvDashboardController extends AbstractController
{

#[Route("/TV/dashboard", name: "tv_dashboard", methods: ['GET'])]
public function index(WardRepository $repository): Response
{
    return $this->render('tv_dashboard/index.html.twig',[
        'wards'=>$repository->findAll()
    ]);
}

#[Route("/TV/dashboard/emerg", name: "tv_dashboard_emerge", methods: ['GET'])]
public function atEmergency(WardRepository $repository): Response
{
    return $this->render('tv_dashboard/tv_emergency.html.twig',[
        'wards'=>$repository->getWardsInEmrg()
    ]);
}

#[Route("/TV/dashboard/peda", name: "tv_dashboard_peda", methods: ['GET'])]
public function atPedaitrics(WardRepository $repository): Response
{
    return $this->render('tv_dashboard/tv_pediatrics.html.twig',[
        'wards'=>$repository->getWardsInPeda()
    ]);
}

#[Route("/TV/dashboard/optha", name: "tv_dashboard_optha", methods: ['GET'])]
public function atOpthamology(WardRepository $repository): Response
{
    return $this->render('tv_dashboard/tv_opthamology.html.twig',[
        'wards'=>$repository->getWardsInOPtha()
    ]);
}
}