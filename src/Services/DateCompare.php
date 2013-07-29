<?php
namespace Services;
/*
 * Ritorn an array of date ordered by chronological order.
 * 
 * @author Gabriele D'Arrigo - @acirdesign
 */
class DateCompare {

    /**
     * Return an array ordered by date. 
     * 
     * @param type $array
     * @return type
     */
    public static function compare($array) {
        uasort($array, function ($first, $second) {
                    return strtotime($second) - strtotime($first);
                });
                
        return $array;
    }

}
