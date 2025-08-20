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
            echo json_encode(["status" => "ok"]);        
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
        }elseif($input["accion"] == "aprobarComprobante"){
            $idCuota = $input["idCuota"];
             if($con->aprobarComprobantePago($idCuota)){
                echo json_encode(["status" => "ok"]);
                exit;
            }else{
                echo json_encode(["status" => "error", "mensaje" => "No se pudo guardar en la base de datos."]);
                exit;
            }
        }elseif($input["accion"] == "denegarComprobante"){
            $idCuota = $input["idCuota"];
             if($con->denegarComprobantePago($idCuota)){
                echo json_encode(["status" => "ok"]);
                exit;
            }else{
                echo json_encode(["status" => "error", "mensaje" => "No se pudo guardar en la base de datos."]);
                exit;
            }
        }else{
            echo json_encode(["message" => "Error en la accion"]);
        }

        break;

        

    case "GET":
        if($_GET["accion"] == "getPagosVacios"){
            $data = $con->getPagosVacios($_SESSION["id"]);
            echo json_encode(["message" => $data]);
            exit;
        }elseif($_GET["accion"] == "getPagosComprobante"){
            $data = $con->getPagosComprobante();
            echo json_encode($data);
            exit;
        }
        else{
            echo json_encode(["message" => "Error en la accion"]);
            exit;
        }
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;

}


?>