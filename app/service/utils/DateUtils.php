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
    public static function addTimeToDate(DateTime $d, $time){
        $arr = split(':', $time);
        $h = $arr[0];
        $m = $arr[1];
        if(count($arr) == 3){
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
}
