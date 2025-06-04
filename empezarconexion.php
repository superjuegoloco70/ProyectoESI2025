<?php

//Versión 0.1.2

class db{

    public $conn;
    public function __construct() {
        $this->conn = new mysqli("localhost","root","","dbname");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

}

?>