<?php

/**
 * DateUtils.php
 */

/**
 * DateUtils
 *
 * @author luis
 * @since Feb 19, 2014
 */
class DateUtils {

    public static function addTimeToDate(DateTime $d, $time) {
        $arr = split(':', $time);
        $h = $arr[0];
        $m = $arr[1];
        if (count($arr) == 3) {
            $s = $arr[2];
            $d->setTime($h, $m, $s);
        } else {
            $d->setTime($h, $m, 0);
        }
    }

    public static function toDataBaseDate(DateTime $df) {
        return $df->format('Y-m-d');
    }

    public static function toDataBaseDateTime(DateTime $df) {
        return $df->format('Y-m-d H:i:s');
    }

    /**
     * 
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return string
     */
    public static function getAllocatedTime($startDate, $endDate) {

        if ($startDate != null && $endDate != null) {
            $diff = $endDate->getTimestamp() - $startDate->getTimestamp();
            $hour = intval($diff / 3600);
            $min = intval(( $diff % 3600) / 60);
            return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($min, 2, '0', STR_PAD_LEFT);
        }

        return '';
    }

    /**
     * @param int $qtd
     * @return DateTime[]
     */
    public function createArrayOfDates($qtd) {
        // Work Array
        $work = array();

        // Get current date
        $month = date("m");
        $day = date("d");
        $year = date("Y");

        // Loop generating dates
        for ($i = 1; $i <= $qtd; $i++) {
            $str = date('Y-m-d', mktime(0, 0, 0, $month, ($day - $i), $year));
            $work[] = DateTime::createFromFormat('Y-m-d', $str);
        }

        // Return
        return $work;
    }

    /**
     * 
     * @param DateTime $date
     * @return DateTime
     */
    public function getNextDate(DateTime $date = null) {

        if ($date == null) {
            $date = new DateTime();
        }

        // Get current date
        $month = $date->format('m');
        $day = $date->format('d');
        $year = $date->format('Y');

        $str = date('Y-m-d', mktime(0, 0, 0, $month, ($day + 1), $year));

        $next = DateTime::createFromFormat('Y-m-d', $str);

        return $next;
    }

    /**
     * @param DateTime $date
     * @return DateTime
     */
    public function getPreviousDate(DateTime $date = null) {

        if ($date == null) {
            $date = new DateTime();
        }

        // Get current date
        $month = $date->format('m');
        $day = $date->format('d');
        $year = $date->format('Y');

        $str = date('Y-m-d', mktime(0, 0, 0, $month, ($day - 1), $year));

        $previous = DateTime::createFromFormat('Y-m-d', $str);

        return $previous;
    }

    /**
     * 
     * @param {string|object} $value
     * @return float
     */
    public function getFloat($value) {
        if (is_object($value) && $value instanceof DateTime) {
            $value = $value->format("H:i:s");
        }

        $timestamp = strtotime($value);

        $hour = date('H', $timestamp);
        $minute = date('i', $timestamp);
        $second = date('s', $timestamp);

        return $hour + ($minute / 60) + ($second / 60 / 60);
    }

    /**
     * @param float $float
     * @return string
     */
    public static function getHour($float) {
        
        $isNegative = false;
        
        if ($float < 0) {
            $float = $float * -1;
            $isNegative = true;
        }

        $hour = (int) $float;
        $min = ($float - $hour) * 60;
        
        $str = str_pad($hour, 2, 0, STR_PAD_LEFT) . ':' . str_pad(round($min, 0), 2, 0, STR_PAD_LEFT);
        
        if ($isNegative) {
            $str = '-' . $str;
        }
        return $str;
    }

}
