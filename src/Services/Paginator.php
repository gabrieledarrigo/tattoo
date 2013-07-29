<?php

namespace Services;

use \Pagerfanta\Pagerfanta;
use \Pagerfanta\Adapter\ArrayAdapter;

/**
 * Return an istance of Pagerfanta paginator.
 * @author Gabriele D'Arrigo - @acirdesign
 */
class Paginator {

    public static function getPaginator($array) {
        $adapter = new ArrayAdapter($array);
        $paginator = new Pagerfanta($adapter);

        return $paginator;
    }

}