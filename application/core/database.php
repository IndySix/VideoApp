<?php 
/**
* MartensMCV is an simple and smal framework that make use of OOP and MVC patern.
* Copyright (C) 2012 Maikel Martens
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('__SITE_PATH')) exit('No direct script access allowed');
/**
 * MartensMVC
 *
 * An MVC framework for PHP/MYSQL
 *
 * @author      Maikel Martens
 * @copyright           Copyright (c) 20012 - 2012, Martens.me.
 * @license     http://martens.me/license.html
 * @link        http://martens.me
 * @since       Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * Database class
 *
 * Database class for MYSQL uses PDO.
 *
 * @package     MartensMCV
 * @subpackage          Core
 * @category            Core
 * @author      Maikel Martens
 */
// ------------------------------------------------------------------------
class database {

    private $dbh;
    private $stmt;
    private $where_field = null;
    private $where_value = null;
    private $orderby_field = null;
    private $orderby_sort = null;

    /**
     * Constructer
     *
     * Create the PDO object and store it in $dbh
     *
     * @access  public
     * @return  void
     */
    public function __construct() {
        include __CONFIG_PATH . 'database.php';

        $this->dbh = new PDO(
                        "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $database,
                        $user,
                        $password,
                        array(PDO::ATTR_PERSISTENT => true)
        );
    }

    /**
     * Query
     *
     * Qeury the given sql statement, if there are binds in the query the
     * can be suplied by the second param as array
     *
     * @access  public
     * @param   string  mysql query
     * @param   array   binds
     * @return  void
     */
    public function query($query, $binds = array()) {

        /* check if binds in qeury equals array lenght */
        if ($this->countBinds($query) != count($binds)) {
            throw new Exception("Binds in qeury are not equal to binds array");
        }

        /* prepare statement */
        $this->stmt = $this->dbh->prepare($query);

        /* Bind binds */
        $bindPostion = 1;
        foreach ($binds as $value) {
            $this->bind($bindPostion, $value);
            $bindPostion++;
        }

        /* Execute statement */
        $this->stmt->execute();
        return $this->stmt->fetchAll();
    }

    /**
     * Rest
     *
     * Reset the where and orderBy.
     *
     * @access  public
     * @return  void
     */
    public function reset(){
        $this->where_field = null;
        $this->where_value = null;
        $this->orderby_field = null;
        $this->orderby_sort = null;
    }

    /**
     * where
     *
     * Sets the where condition for the get, delete, update functions.
     *
     * @access  public
     * @param   string  field
     * @param   array   value
     * @return  void
     */
    public function where($field, $value) {
        $this->where_field = $field;
        $this->where_value = $value;
    }

    /**
     * orderby
     *
     * Sets the order by for the get function.
     *
     * @access  public
     * @param   string  mysql query
     * @param   array   binds
     * @return  void
     */
    public function orderBy($field, $sort = 'DESC'){
        $this->orderby_field = $field;
        $this->orderby_sort = $sort;
    }

    /**
     * get function
     *
     * Get array from the given table.
     *
     * @access  public
     * @param   string  Database table name
     * @param   int     How many rows, default null
     * @param   int     From wich row, default 0
     * @return  Array with row arrays
     */
    public function get($table, $rows = null, $fromRow = 0) {
        $query = "SELECT * FROM " . $table;
        $whereBind = array();

        /* Add WHERE when $where_field and $where_value are set */
        if (!empty($this->where_field)) {
            $query .= " WHERE " . $this->where_field . " =  ? ";
            $whereBind[] = $this->where_value;
        }

        /* Add ORDER BY when $this->orderby_field and $this->orderby_sort are set */
        if(!empty($this->orderby_field) && !empty($this->orderby_sort)){
            $query .= " ORDER BY ".$this->orderby_field." ".$this->orderby_sort;
        }

        /* Add LIMIT when given in function */
        if ($rows != null && is_int($rows) && is_int($fromRow)) {
            $query .=" LIMIT " . $fromRow . " , " . $rows;
        }
        return $this->query($query, $whereBind);
    }

    /**
     * delete
     *
     * Delete a record in database, where must been set
     *
     * @access  public
     * @param   string  table
     * @return  void
     */
    public function delete($table) {
        $query = "DELETE FROM " . $table;
        $whereBind = array();

        /* Checks if $where_field and $where_value are set */
        if ($this->where_field == null || $this->where_value == null) {
            throw new Exception("WHERE not set delete function only removes one record!");
        }

        $query .=" WHERE " . $this->where_field . " = ?";
        $whereBind[] = $this->where_value;

        return$this->query($query, $whereBind);
    }

    /**
     * insert
     *
     * insert record in table, insert data is supplied in array where key is table field
     * 
     * $insert['id'] = null;
     * $insert['title'] = "Some text";
     *
     * @access  public
     * @param   string  table
     * @param   array   field => values
     * @return  void
     */
    public function insert($table, $values = array()) {
        $query = "INSERT INTO " . $table . " (";
        $fieldNumbers = count($values);

        /* Checks if there are values */
        if ($fieldNumbers == 0) {
            throw new Exception("No values where given!");
        }

        /* Add insert fields to qeury */
        $firstValueSet = false;
        foreach ($values as $key => $value) {
            if (!$firstValueSet) {
                $query .= " " . $key;
                $firstValueSet = true;
            } else {
                $query .= "," . $key . " ";
            }
        }

        $query .= ") VALUES (";

        /* Add ? for binding the data */
        $firstValueSet = false;
        foreach ($values as $key => $value) {
            if (!$firstValueSet) {
                $query .= " ?";
                $firstValueSet = true;
            } else {
                $query .= ", ?";
            }
        }

        $query .= ")";
        return $this->query($query, $values);
    }

    /**
     * update
     *
     * update record in table, update data is supplied in array where key is table field
     * 
     * $update['id'] = null;
     * $update['title'] = "Some text";
     *
     * @access  public
     * @param   string  table
     * * @param array   field => values
     * @return  void
     */
    public function update($table, $values = array()) {
        $query = "UPDATE " . $table . " SET ";
        $fieldNumbers = count($values);

        /* Checks if $where_field and $where_value are set */
        if ($this->where_field == null || $this->where_value == null) {
            throw new Exception("WHERE not set for update!");
        }

        /* Checks if there are values */
        if ($fieldNumbers == 0) {
            throw new Exception("No values where given!");
        }

        /* Add update fields to qeury */
        $firstValueSet = false;
        foreach ($values as $key => $value) {
            if (!$firstValueSet) {
                $query .= " " . $key . " = ?";
                $firstValueSet = true;
            } else {
                $query .= ", " . $key . " = ?";
            }
        }

        /* Add the where_value to the binds */
        $values[] = $this->where_value;
        $query .= " WHERE " . $this->where_field . " = ?";

        return $this->query($query, $values);
    }
    
    public function numRows($table) {
        $query = "SELECT count(*) FROM ".$table;
        $result = $this->query($query);
        if(isset($result[0][0])){
            return $result[0][0];
        }
        return false;
    }

    /**
     * bind
     *
     * Determinate the type of the value en bind it
     *
     * @access  public
     * @param   string  bind postition 
     * @param   string  value
     * @param   string  type
     * @return  void
     */
    private function bind($pos, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($pos, $value, $type);
        return $this;
    }

    /**
     * countBinds
     *
     * Determinate the amount af binds '?' in query
     *
     * @access  public
     * @param   string  query
     * @return  void
     */
    private function countBinds($qeury) {
        $count = 0;
        for ($i = 0; $i < strlen($qeury); $i++) {
            if (substr($qeury, $i, 1) == '?') {
                $count++;
            }
        }
        return $count;
    }

}

/* End of file DB.php */
/* Location: ./application/core/database.php */