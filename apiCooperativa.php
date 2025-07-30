<?php

include "conexion.php";

$con = new db();

header("Content-Type: application/json");
$method = $_SERVER["REQUEST_METHOD"];
$input = json_decode(file_get_contents("php://input"), true);

switch ($method){
    case "PUT":
        if(isset($_POST["fecha"]) && isset($_POST["horas"])){
            $con->registerHours($_POST["fecha"], $_POST["horas"]);
            echo json_encode(["message" => "Horas Registradas Correctamente"]);
        }else{
            echo json_encode(["message" => "Error en el registro de horas"]);
        }
    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;

}


?>