<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "conexion.php";

header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER["REQUEST_METHOD"];


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



$con = new db();

if(!isset($_SESSION["id"])){
    $_SESSION["id"] = "0";
}

switch ($method){
    case "GET":
        //Iniciar Sesión
        if($_GET["accion"] == "login"){
            if($_SESSION["id"] != "0"){
                $result = $con->checkApproved($_GET["ci"]);
                if($result == 0){
                        echo json_encode(["redirect" => "esperandoaprobacion.html"]);
                        exit;
                }elseif($result == 1){
                    echo json_encode(["redirect" => "usuarios.html"]);
                    exit;
                }elseif($result == 2){
                    echo json_encode(["redirect" => "admins.html"]);
                    exit;
                }else{
                    echo json_encode(["message" => "Error"]);
                    exit;
                }
            }
            $data = $con->checkCiPass($_GET["ci"]);
            if($_GET["passwd"] == $data["Contrasena"]){  
                $result = $con->checkApproved($_GET["ci"]);
                if($result == 0){
                    $_SESSION["id"] = $_GET["ci"]; 
                    echo json_encode(["redirect" => "esperandoaprobacion.html"]);
                    exit;
                }elseif($result == 1){
                    $_SESSION["id"] = $_GET["ci"]; 
                    echo json_encode(["redirect" => "usuarios.html"]);
                    exit;
                }else{
                    echo json_encode(["message" => "Error"]);
                    exit;
                }
            }else{
                echo json_encode(["message" => "Error en el inicio de sesion"]);
                exit;
            }
        //Obtener a las personas con Estado = 0
        }elseif($_GET["accion"] == "getWaiting"){
            $data = $con->getWaiting();
            echo json_encode(["message" => $data]);  
            exit;
        }else{
            echo json_encode(["message" => "Error en la accion" . $_GET["accion"]]);
            exit;
        }
        break;
     case 'POST':
        //Registro Usuario
        if($input["accion"] == "registrar"){
            if($_SESSION["id"] != "0"){
                $result = $con->checkApproved($_SESSION["id"]);
                if($result == 0){
                    echo json_encode(["redirect" => "esperandoaprobacion.html"]);
                    exit;
                }elseif($result == 1){
                    echo json_encode(["redirect" => "usuarios.html"]);
                    exit;
                }elseif($result == 2){
                    echo json_encode(["redirect" => "admins.html"]);
                    exit;
                }else{
                    echo json_encode(["message" => "Error"]);
                    exit;
                }
            }
            $name = $input['name'];
            $id = $input['ci'];
            $passwd = $input['passwd'];
            if($id != null && $passwd != null && $name != null){
                $result = $con->newUser($name, $id, $passwd);
                if ($result == true){
                    $_SESSION["id"] = $id;
                    echo json_encode(["redirect" => "esperandoaprobacion.html"]);
                    exit;
                }else{
                    echo json_encode(["message" => "El usuario ya existe"]);
                    exit;
                }
            }else{
                echo json_encode(["message" => "Error en el registro"]);
                exit;
            }
        //Actualizar el estado a 1
        }elseif($input["accion"] == "Aprobar"){
            $id = $input['CI'];
            if($con->aprobarUsuario($id)){
                echo json_encode(['success' => true]);
            }else{
                echo json_encode(['success' => false, 'error' => "Error al actualizar"]);
            }
            
        }else{
            echo json_encode(["message" => "Error"]);
            exit;
        }
        
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}





?>