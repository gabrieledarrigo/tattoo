<?php

namespace Models;

use Silex\Application;

/**
 * Tattoo.
 * Expose method to retrieve all items 
 * or to CUD them.
 */
class Tattoo {

    protected $db;
    protected $table = 'tattoo';
    
    /**
     * Application is the dependency of this class.
     * @param \Silex\Application $app
     */
    public function __construct(Application $app) {
        $this->db = $app['db'];
    }
    
    /**
     * Retrieve all items.
     *  
     * @return type
     */
    public function getAll() {
        $sql = "SELECT id, title, description, image,
                published, DATE_FORMAT(date_upload, '%d/%m/%Y') AS date_upload
                FROM $this->table";

        $data = $this->db->fetchAll($sql);

        return $data;
    }
    
    /**
     * Retrieve a single item.
     * 
     * @param type $id
     * @return type
     */
    public function getById($id) {
        $safeId = $this->db->quote($id, \PDO::PARAM_INT);

        $sql = "SELECT id, title, description, image,
                published, DATE_FORMAT(date_upload, '%d/%m/%Y') AS date_upload
                FROM $this->table
                WHERE id = $safeId";

        $data = $this->db->fetchAssoc($sql);

        return $data;
    }
    
    /**
     * Insert an item.
     * 
     * @param type $params
     * @return type
     */
    public function insert($params) {
        return $this->db->insert($this->table, $params);
    }
    
    /**
     * Update an item.
     * @param type $id
     * @param type $params
     * @return type
     */
    public function update($id, $params) {
        $safeId = $this->db->quoteIdentifier($id, \PDO::PARAM_INT);

        return $this->db->update($this->table, $params, array('id' => $id));
    }

    public function delete($id) {
        $safeId = $this->db->quote($id, \PDO::PARAM_INT);

        return $this->db->delete('biography', array('id' => $id));
    }

}