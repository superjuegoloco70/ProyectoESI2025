<?php



class db{

    public $conn;
    public function __construct() {
        $this->conn = new mysqli("127.0.0.1:3306","root","","3mhdr");
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