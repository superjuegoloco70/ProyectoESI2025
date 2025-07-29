<?php

include "conexion.php";

header("Content-Type: application/json");
$method = $_SERVER["REQUEST_METHOD"];
$input = json_decode(file_get_contents("php://input"), true);

switch ($method){

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;

}


?>