<?php



class db{

    public $conn;
    public function __construct() {
        $this->conn = new mysqli("localhost:3306","root","","3mhdr");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

}

?>