<?php

/**
 * @param DateTime $date
 * @param string $format use NULL for default pathern
 * @return type
 */
function formatDate(DateTime $date, $format = NULL) {

    $pattern = 'd/m/Y H:i:s';

    if ($format != NULL) {
        switch ($format) {
            case 'long':
                $pattern = 'M, d \de Y H:i:s';
                break;
            case 'only-day':
                $pattern = 'd/m/Y';
                break;
        }
    }
    
    return $date->format($pattern);
}
