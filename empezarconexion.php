<?php



class db{

    public $conn;
    public function __construct() {
        $this->conn = new mysqli("localhost","root","","");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($q)
    {
        return $this->conn->query($q);
    }

}

?>