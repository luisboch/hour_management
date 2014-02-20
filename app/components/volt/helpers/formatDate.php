<?php

/**
 * @param DateTime $date
 * @param string $format use NULL for default pathern
 * @return type
 */
function formatDate(DateTime $date = null, $format = NULL) {

    if ($date == null) {
        return '';
    }


    if ($format != NULL) {
        switch ($format) {
            case 'long':
                $pattern = 'M, d \de Y H:i:s';
                break;
            case 'day-only':
                $pattern = 'd/m/y';
                break;
            case 'time-only':
                $pattern = 'H:i:s';
                break;
            case 'HM-only':
                $pattern = 'H:i';
                break;
            case 'short-date':
            default:
                $pattern = 'd/m/y H:i';
                break;
        }
    } else {
        $pattern = 'd/m/y H:i';
    }

    return $date->format($pattern);
}
