<?php



    class db{

        public $conn;
        public function __construct() {
            $this->conn = new mysqli("localhost:3306","root","","3mhdr");
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }

        public function query($q)
        {
            return $this->conn->query($q);
        }

        public function checkCiPass($id, $passwd){
            $query = "Select Contrasena from usuarios where CI=$id";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            return $data;
        }

        public function newUser($name, $id, $passwd){
            $query = "Select * from usuarios where CI=$id";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            if($data["CI"] != $id){
                $query = "INSERT INTO usuarios (CI, Nombre, Contrasena, Estado) VALUES ('$id', '$name', '$passwd', 'N/A')";
                $this->conn->query($query);
                $res = json_encode(["message" => "usuario Registrado"]);
            }else{
                $res = json_encode(["message" => "El usuario ya existe"]);
            }
            return $res;
        }
    }

?>