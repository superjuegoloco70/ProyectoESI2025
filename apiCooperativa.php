<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "conexion.php";

$con = new db();

header("Content-Type: application/json");
$input= json_decode(file_get_contents("php://input"), true);
$method = $_SERVER["REQUEST_METHOD"];



switch ($method){
    case "POST":
        if($input["accion"] == "registrar"){
            $con->registerHours($input["fecha"], $input["horas"]);
            echo json_encode(["message" => "Horas Registradas Correctamente"]);
        }else{
            echo json_encode(["message" => "Error en el registro de horas"]);
        }
        break;
    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;

}


?>