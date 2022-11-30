<?php
namespace App\Helper;
use DateTime;
use Andegna\DateTime as AD;
use App\Entity\PlanningQuarter;
use Doctrine\ORM\EntityManagerInterface;

class AmharicHelper 
{
    public static function fromGretoEthstr($gregorian)
    {
        $ethipic = new AD($gregorian);
        return $ethipic->format('F j Y');
    }
    public static function fromGretoEthstrint($gregorian)
    {
        # code..
        $ethipic = new AD($gregorian);
        return $ethipic->format('F j');
    }
      public static function fromEthtoGre($ethipic)
    {
        # code..

        return $ethipic->toGregorian();
    }
     public static function getCurrentYear()
    {
        $gregorian=new DateTime();
        $ethipic = new AD($gregorian);
        // dump($gregorian);
        return $ethipic->format('Y');
    }
      public static function getCurrentYearPara($gregorian)
    {
        $ethipic = new AD($gregorian);
        // dump($gregorian);
        return $ethipic->format('Y');
    }
    
     public static function getCurrentDay()
    {
        $gregorian = new DateTime();

        $ethipic = new AD($gregorian);
        // dump($gregorian);
        return $ethipic->format('j');
    }
     public static function getCurrentMonth()
    {
        $gregorian = new DateTime();

        $ethipic = new AD($gregorian);
        // dump($gregorian);
        return $ethipic->format(DATE_ATOM);
    }
    //get current Quarter
    public static function getCurrentQuarter( EntityManagerInterface $em
    )
    {
        $quarterId=0;
        $time = new DateTime('now');

        $quarters = $em->getRepository(PlanningQuarter::class)->findAll();
        foreach ($quarters as $quarter) {
            if ($time >= $quarter->getStartDate() && $time < $quarter->getEndDate()) {
                $quarterId = $quarter->getId();
            }
        }
        return $quarterId;

    }
    
}
