<?php

namespace App\Helper;

use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Andegna\DateTime as AD;

class Utils
{
    /**
     * Convert datetime to int age
     * @param DateTime $birthdate
     * @return Int
     */
    public static function age($birthdate, $type = 0)
    {

        $birthday = $birthdate->format('Y-m-d');
        list($year, $month, $day) = explode("-", $birthday);
        $year_diff  = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff   = date("d") - $day;

        // 
        // dd(date("m Y"));

        if ($day_diff < 0 && $month_diff == 0) $year_diff--;
        if ($day_diff < 0 && $month_diff < 0) $year_diff--;
        // dd($year_diff);
        $today = new Datetime(date('m.d.y'));
        $diff = $today->diff($birthdate);

        // dd($year_diff);
        if ($type == 2) {
            $diff = $today->diff($birthdate);
            return "" . $diff->y . " years, " . $diff->m . " month," . $diff->d . " days";
        }
        if ($type == 1) {
            $diff = $today->diff($birthdate);
            return "" . $diff->y . " years, " . $diff->m . " month";
        }

        if ($type == 3) {
            $diff = $today->diff($birthdate);
            return "" . $diff->y;
        }


        // dd($year_diff);
        // dd($month_diff);
        if ($diff->y <= 0) {
            // $diff = $today->diff($birthdate);
            if ($diff->m <= 0) {
                $diff = $today->diff($birthdate);
                return $diff->d . ($diff->d > 1 ? " days" : " day");
            } else {
                return $diff->m . ($diff->m > 1 ? " months" : " month");
            }
        } else {
            return $year_diff;
        }


        // dd($year_diff);
    }


    /**
     * Convert datetime to int age
     * @param DateTime $birthdate
     * @return Int
     */
    public static function age2($birthdate, $type = 0)
    {
        $birthday = $birthdate->format('Y-m-d');
        list($year, $month, $day) = explode("-", $birthday);
        $year_diff  = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff   = date("d") - $day;
        if ($day_diff < 0 && $month_diff == 0) $year_diff--;
        if ($day_diff < 0 && $month_diff < 0) $year_diff--;
        $today = new Datetime(date('m.d.y'));
        if ($type == 2) {
            $diff = $today->diff($birthdate);
            return "" . $diff->y . " years, " . $diff->m . " month," . $diff->d . " days";
        }
        if ($type == 1) {
            $diff = $today->diff($birthdate);
            return "" . $diff->y . " years, " . $diff->m . " month";
        }

        if ($year_diff <= 0) {
            $diff = $today->diff($birthdate);
            return "0." . $diff->m;
        };
        return $year_diff;
    }
    public static function fromGretoEth($gregorian)
    {
        $ethipic = new AD($gregorian);
        return $ethipic;
    }
   


    public static function ago($datetime, $full = false)
    {

        $datetime = $datetime->format('Y-m-d H:i:s');
        $now = new DateTime('now');
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
    public static function nowEth()
    {
        $gregorian = new DateTime();
        $ethipic = new AD($gregorian);
        return $ethipic->format('Y-m-d');
    }

 
}
