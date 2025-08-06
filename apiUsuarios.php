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
        if($_GET["accion"] == "login" && $_SESSION["id"] == "0"){
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
        }elseif($_SESSION["id"] != "0"){
            $result = $con->checkApproved($_GET["ci"]);
            if($result == 0){
                    echo json_encode(["redirect" => "esperandoaprobacion.html"]);
                    exit;
            }elseif($result == 1){
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
        break;
     case 'POST':
        //Registro Usuario
        if($input["accion"] == "registrar"){
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
            }elseif($_SESSION["id"] != "0"){
                $result = $con->checkApproved($_SESSION["id"]);
                if($result == 0){
                    echo json_encode(["redirect" => "esperandoaprobacion.html"]);
                    exit;
                }elseif($result == 1){
                    echo json_encode(["redirect" => "usuarios.html"]);
                    exit;
                }else{
                    echo json_encode(["message" => "Error"]);
                    exit;
                }
            }else{
                echo json_encode(["message" => "Error en el registro"]);
                exit;
            }
        }else{
            echo json_encode(["message" => "Error en el registro"]);
            exit;
        }
        
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}





?>