<?php

namespace App\Twig;

use App\Helper\Utils;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\Form\FormView;
use DateTime;
use Andegna\DateTime as eth_date;
use Andegna\Constants;
use Andegna\DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
class AppExtension extends AbstractExtension
{

    private $entityManager;
    private $urlGeneratorInterface;
    private $templating;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $urlGeneratorInterface, \Twig\Environment $templating)
    {

        $this->entityManager = $em;
        $this->urlGeneratorInterface = $urlGeneratorInterface;
        $this->templating = $templating;
    }
 public function getFilters(): array
    {
        return [
       
            new TwigFilter('eth_date', [$this, 'toEthiopianDate']),
            new TwigFilter('age', [$this, 'age']),
        ];
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('eth_date', [$this, 'toEthiopianDate']),
            new TwigFunction('eth_date2', [$this, 'toEthiopianDate2']),
            new TwigFunction('age', [$this, 'age']),
            new TwigFunction('age2', [$this, 'age2']),
            new TwigFunction('dateSub', [$this, 'dateSub']),
            new TwigFunction('ago', [$this, 'ago']),
            
        ];
    }

    public function toEthiopianDate(\DateTime $value=null)
    {
        if($value==null){
            $value=new DateTime('now');
        }
        return (new eth_date($value))->format("l, M  d/Y");
    }
  
    public function toEthiopianDate2(\DateTime $value=null)
    {
        if($value=null){
            $value=new DateTime('now');
        }
      //  return (new eth_date($value))->format("l, M  d/Y h:i A");
        return (new eth_date($value))->format("d/m/Y");
    }
  
    
    
    /*
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
            new TwigFilter('cast_to_array', [$this, 'objectFilter']),

        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('age', [$this, 'age']),
            new TwigFunction('age2', [$this, 'age2']),
            new TwigFunction('dateSub', [$this, 'dateSub']),
            new TwigFunction('ago', [$this, 'ago']),

            //new TwigFunction('mul', [$this, 'calculateArea']),
        ];
    }
    */

    public function getConsumableCategory()
    {
     return $this->entityManager->getRepository(ConsumableCategory::class)->findAll();   
    }

    function opdNotification($user)
    {
        return OPD::loadNotification($user, $this->entityManager, $this->urlGeneratorInterface);
    }
    function opdBadge(Encounter $encounter)
    {
        //dd(OPD::badge($encounter, $this->entityManager));
        return OPD::badge($encounter, $this->entityManager);
    }


    public function fromGretoEth($gregorian)
    {

        return Utils::fromGretoEth($gregorian);
    }
    public function fromGretoEthstr($gregorian)
    {
        # code..
        $ethipic = new AD($gregorian);
        dump($gregorian);
        return $ethipic->format('F j Y');
    }
    public function fromGretoEthstrint($gregorian)
    {
        # code..
        $ethipic = new AD($gregorian);
        return $ethipic->format('F j');
    }
    public function nowEth()
    {
        
        return Utils::nowEth();
    }
    public function fromGretoEthstrM($gregorian)
    {
        # code..
        $ethipic = new AD($gregorian);
        return $ethipic->format('F');
    }
    public function fromEthtoGre($ethipic)
    {
        # code..
        return $ethipic->toGregorian();
    }


    public function getNextSchedule($date, $plus)
    {
        $next = clone $date;
        $next = $next->modify("+ $plus day");
        $ethipic = new AD($next);
        return $ethipic->format('F j Y D');
        // return $next->format("Y-m-d");
    }
    public function missed($date)
    {
        $now = new DateTime;
        if ($date < $now)
            return true;
        else
            return false;
    }


    public function dateSub($from,$to)
    {
        $start_t = $from;
        $current_t = $to;
        $difference = $start_t ->diff($current_t );
        $return_time = $difference ->format('%H:%I:%S');
        return $return_time;
    }
    public function ago($datetime, $full = false)
    {
        $datetime = $datetime->format('Y-m-d H:i:s');
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }


    public function canTake($date, $plus)
    {
        $next = clone $date;
        $next = $next->modify("+ $plus day");
        $now = new DateTime;

        // return $next->format("Y-m-d");
        $diff = $now->diff($next);

        return  $now;
    }



    function age($birthdate, $type = 0)
    {

        if ($birthdate == null) return "Undefined";
        return Utils::age($birthdate, $type);
    }
    function radiologyMenu()
    {

        $allmachine = $this->entityManager->getRepository(RadiologyMachineType::class)->find(3);

        $res = $this->templating->render("menus/radrequestmenu.html.twig", ["radiology_requests" => $allmachine]);
        return $res;
    }
    function age2($birthdate, $type = 0)
    {
        // return($birthdate);
        if ($birthdate == null) return "Undefined";
        return Utils::age2($birthdate, $type);
    }
    function countIssued()
    {
        
         $total_days=General::getIssuedCount($this->entityManager);
         return($total_days);
            // if ($birthdate == null) return "Undefined";
        // return Utils::age2($birthdate, $type);
    }

    /*public function objectFilter($stdClassObject) {
        // Just typecast it to an array
        $arr=array();
        $response = (array)$stdClassObject;
        foreach($response as $key => $value) 
        {
            $arr[ $key]=$value;
        }
        return $arr;
    }*/
}

