<?php

namespace Models;

use Silex\Application;

/**
 * Biography.
 * Expose method to retrieve all items 
 * or to CUD them.
 */
class Biography extends AbstractModel {

    protected $db;
    protected $table = 'biography';
    
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
                published, DATE_FORMAT(date, '%d/%m/%Y') AS date
                FROM $this->table
                ORDER BY id DESC";

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
                published, DATE_FORMAT(date, '%d/%m/%Y') AS date 
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
    
    /**
     * Delete an item from the table.
     * 
     * @param type $id
     * @return type
     */
    public function delete($id) {
        $safeId = $this->db->quote($id, \PDO::PARAM_INT);

        return $this->db->delete('biography', array('id' => $id));
    }

}