<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "conexion.php";

$con = new db();

header("Content-Type: application/json");
$input= json_decode(file_get_contents("php://input"), true);
$method = $_SERVER["REQUEST_METHOD"];

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

switch ($method){
    case "POST":
        if($input["accion"] == "registrar"){
            $con->registerHours($input["fecha"], $input["horas"]);
            echo json_encode(["message" => "Horas Registradas Correctamente"]);        
        }elseif($input["accion"] == "subirComprobante"){
            $idCuota = $input["idCuota"];
            $comprobante = $input["comprobante"];
            if (preg_match('/^data:image\/(\w+);base64,/', $comprobante, $type)) {
                $comprobante = substr($comprobante, strpos($comprobante, ',') + 1);
                $comprobanteBinario = base64_decode($comprobante);

                if ($comprobanteBinario === false) {
                    echo json_encode(["status" => "error", "mensaje" => "No se pudo decodificar la imagen."]);
                    exit;
                }
                
                if($con->agregarComprobantePago($idCuota, $comprobanteBinario)){
                    echo json_encode(["status" => "ok"]);
                    exit;
                }else{
                    echo json_encode(["status" => "error", "mensaje" => "No se pudo guardar en la base de datos."]);
                    exit;
                }
            }else{
                echo json_encode(["status" => "error", "mensaje" => "Formato de imagen no válido."]);
                exit;
            }

            exit;
        }else{
            echo json_encode(["message" => "Error en la accion"]);
        }

        break;

        

    case "GET":
        if($_GET["accion"] == "getPagos"){
            $data = $con->getPagos($_SESSION["id"]);
            echo json_encode(["message" => $data]);
            exit;
        }else{
            echo json_encode(["message" => "Error en la accion" . $_GET["accion"]]);
            exit;
        }
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;

}


?>