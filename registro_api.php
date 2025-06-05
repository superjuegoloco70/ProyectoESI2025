<?php


    include "empezarconexion.php";

    class api{

        private $db;
        public function __construct() {
            $this->db = (new db())->conn;
        }
        

        public function registrarUsuario($ci, $nombre, $contra){
            $resFinal = false;
            $statement = $this->db->query("select * from usuarios where CI=" . $ci);
            $resStatement= $statement->fetch_assoc();
            if($resStatement != null){
                $resFinal = false;
            }else{
                $resFinal = true;
                $statement = $this->db->query("INSERT INTO usuarios (CI, Nombre, Contraseña) VALUES
                ('$ci', '$nombre', '$contra')");
            }
            return $resFinal;
            
        }

        public function loginUsuario($ci, $contra){
            $resFinal = false;
            $statement = $this->db->query("select Contraseña from usuarios where CI=" . $ci);
            $resStatement= $statement->fetch_assoc();
            if($contra == $resStatement["Contraseña"]){
                $resFinal = true;
            }else{
                $resFinal= false;
            }
            return $resFinal;
        }

    }




?>