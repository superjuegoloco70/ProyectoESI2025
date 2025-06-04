<?php
//Versión 0.1.2

include "empezarconexion.php";

class api{

    private $db;
    public function __construct() {
        $this->db = (new db())->conn;
    }
    

    public function registrarUsuario($ci, $nombre, $contra){
        $statement = $this->db->query("INSERT INTO usuarios (CI, Nombre, Contraseña) VALUES
        ('$ci', '$nombre', '$contra')");
    }

}



?>