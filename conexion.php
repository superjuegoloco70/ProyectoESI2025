<?php

    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }

    class db{

        public $conn;
        public function __construct() {
            $this->conn = new mysqli("localhost:3306","root","","3mhdr");
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }

        public function checkCiPass($id){
            $query = "Select Contrasena from usuarios where CI=$id";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            return $data;
        }

        public function checkApproved($id){
            $res = "";
            $query = "Select Estado from usuarios where CI=$id";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            return $data["Estado"];
        }

        public function newUser($name, $id, $passwd){
            $query = "Select * from usuarios where CI=$id";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            if(!$data){
                $query = "INSERT INTO usuarios (CI, Nombre, Contrasena, Estado) VALUES ('$id', '$name', '$passwd', 0)";
                $this->conn->query($query);
                $res = true;
            }else{
                $res = false;
            }
            return $res;
        }

        public function registerHours($fecha, $horas){
            $query = "INSERT INTO horastrabajadas (FechaTrabajo, N_Horas, CI_Trabajador) VALUES ('$fecha', '$horas', '$_SESSION[id]')";
            $this->conn->query($query); 
        }

        public function getWaiting(){
            $query = "SELECT * FROM usuarios WHERE Estado = 0";
            $result = $this->conn->query($query);
            while($row = mysqli_fetch_assoc($result)){
                $json[] = $row;
            }
            
            return $json;
        }

        public function aprobarUsuario($ci){
            $query = "UPDATE usuarios set Estado = 1 WHERE CI = '$ci'";
            $res = false;
            if($this->conn->query($query)){
                $res = true;
            }else{
                $res = false;
            }

            return $res;

        }
    }

?>