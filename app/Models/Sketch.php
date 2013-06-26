<?php

namespace Models;

class Sketch {

    public function __construct() {
        ;
    }

    public function getAll() {
        $sql = "SELECT * FROM biopic";
        $data = $app['db']->fetchAssoc($sql);

        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die(0);
    }

    public function getById($id) {
        
    }

    public function insert() {
        
    }

    public function update() {
        
    }

    public function delete() {
        
    }

}