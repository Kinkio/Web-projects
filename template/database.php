<?php

require_once 'autoloader.php';

class Database {

    protected $con;
    private $selectOperator;
    private $condition_field;
    private $condition_operator;
    private $condition_value;
    private $condition_limit;
    private $condition_order;
    private $condition_sort;
	
	private $db_pass = "";
	private $db_host = "";
	private $db_user = "";
	
	

    /* -------SETTER AND GETTER--------- */

    public function getSelectOperator() {
        return $this->selectOperator;
    }

    public function setSelectOperator($selectOperator) {
        $this->selectOperator = $selectOperator;
    }

    /* ---------------- */

    public function getCondition_field() {
        return $this->condition_field;
    }

    public function setCondition_field($condition_field) {
        $this->condition_field = $condition_field;
    }

    /* ---------------- */

    public function getCondition_operator() {
        return $this->condition_operator;
    }

    public function setCondition_operator($condition_operator) {
        $this->condition_operator = $condition_operator;
    }

    /* ---------------- */

    public function getCondition_value() {
        return $this->condition_value;
    }

    public function setCondition_value($condition_value) {
        $this->condition_value = $condition_value;
    }

    /* ---------------- */

    public function getCondition_limit() {
        return $this->condition_limit;
    }

    public function setCondition_limit($condition_limit) {
        $this->condition_limit = $condition_limit;
    }

    /* ---------------- */

    public function getCondition_order() {
        return $this->condition_order;
    }

    public function setCondition_order($condition_order) {
        $this->condition_order = $condition_order;
    }

    /* ---------------- */

    public function getCondition_sort() {
        return $this->condition_sort;
    }

    public function setCondition_sort($condition_sort) {
        $this->condition_sort = $condition_sort;
    }

    /* ---------------- */

    /**
     * 
     * @return boolean
     */
    protected function __construct() {
        $this->con = new mysqli("localhost", $db_host, $db_pass, $db_user);
        if (!$this->con) {
            die('kan ikke forbinde (' . $this->con->connect_errno . ')' . $this->con->connect_error);
            return false;
        } else {
            $this->con->query("Set names utf8") or die($this->con->error);
            return true;
        }
    }

    /**
     * CRUD-metode (Create-Read-Update-Delete)
     * @return string
     */
    protected function create($tablename, $fieldnames) {
        $table_name = "";
        $table_value = "";

        foreach ($fieldnames as $fieldname => $fieldvalues) {
            $table_name .= "$fieldname, ";
            $table_value .= "'$fieldvalues', ";
        }
        $table_name = rtrim($table_name, ", ");
        $table_value = rtrim($table_value, ", ");

        $create = "INSERT INTO $tablename ($table_name) VALUES ($table_value)";
        $con = $this->con;

        if ($this->con->query($create) == TRUE) {
            $new_id = mysqli_insert_id($con);     
            return $new_id;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $tablename
     * @param array $fieldnames
     * @param type $condition_field
     * @param type $condition_operator
     * @param type $condition_value
     * @return boolean
     */
    protected function update($tablename, $fieldnames, $condition_field, $condition_operator, $condition_value) {
        $table_name = "";
        if (!$fieldnames == "") {
            foreach ($fieldnames as $fieldname => $fieldvalues) {
                $table_name .= "$fieldname = '$fieldvalues', ";
            }
            $table_name = rtrim($table_name, ", ");
        }
        $update = "UPDATE $tablename SET $table_name WHERE " . $condition_field . " " . $condition_operator . " '" . $condition_value . "'";

        if ($this->con->query($update) == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $tablename
     * @param type $condition_field
     * @param type $condition_operator
     * @param type $condition_value
     * @return boolean
     */
    protected function delete($tablename, $condition_field, $condition_operator, $condition_value) {
        $sql_delete = "DELETE FROM $tablename WHERE " . $condition_field . " " . $condition_operator . " '" . $condition_value . "'";
        print_r($sql_delete);
        if ($this->con->query($sql_delete) == true) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $tablename
     * @param string $selectOperator
     * @param type $condition_field
     * @param type $condition_operator
     * @param type $condition_value
     * @return type
     */
    protected function select_alldb($tablename, $selectOperator = NULL, $condition_field = NULL, $condition_operator = NULL, $condition_value = NULL, $condition_limit = NULL, $condition_order = NULL, $condition_sort = NULL) {
        if ($selectOperator == NULL) {
            $selectOperator = "*";
        }
        $sql_select_db = "SELECT $selectOperator FROM $tablename";

        if (!$condition_field == NULL) {
            $sql_select_db .= " WHERE " . $condition_field . " " . $condition_operator . " '" . $condition_value . "'";
        }

        if (!$condition_order == NULL) {
            $sql_select_db .= " ORDER BY $condition_order";
        }

        if (!$condition_sort == NULL) {
            $sql_select_db .= " $condition_sort";
        }

        if (!$condition_limit == NULL) {
            $sql_select_db .= " LIMIT $condition_limit";
        }
        
        return $this->con->query($sql_select_db);
    }
}
