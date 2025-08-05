<?php
include "conexion.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");
$input = json_decode(file_get_contents("php://input"), true);
$method = $_SERVER["REQUEST_METHOD"];


session_start();

$_SESSION["id"] = "0";

$con = new db();

switch ($method){
    case "GET":
        //Iniciar Sesión
        if($input["accion"] == "login"){
            $id = $input["ci"];
            $passwd=$input["passwd"];
            if($id != null && $passwd != null && $_SESSION["id"] != "0"){
                $data = $con->checkCiPass($id, $passwd);
                if($passwd == $data["Contrasena"]){
                    $_SESSION["id"] = $id;
                    $result = $con->checkApproved($id);
                    if($result == 0){
                        header("Location: esperandoaprobacion.html");
                    }elseif($result == 1){
                        header("Location: usuarios.html");
                    }else{
                        echo json_encode(["message" => "Error"]);
                    }
                }else{
                    echo json_encode(["message" => "Error en el inicio de sesion"]);
                } 
            }elseif($_SESSION["id"] != "0"){
                $result = $con->checkApproved($id);
                if($result == 0){
                    header("Location: esperandoaprobacion.html");
                }elseif($result == 1){
                    header("Location: usuarios.html");
                }else{
                    echo json_encode(["message" => "Error"]);
                }
            }else{
                echo json_encode(["message" => "Error en el inicio de sesion"]);
            }
        }else{
            echo json_encode(["message" => "Error en el inicio de sesion"]);
        }
        break;
     case 'POST':
        //Registro Usuario
        if($input["accion"] == "registrar"){
            $name = $input['name'];
            $id = $input['Ci'];
            $passwd = $input['passwd'];
            if($id != null && $passwd != null && $name != null && $_SESSION["id"] == "0"){
                $result = $con->newUser($name, $id, $passwd);
                if ($result == true){
                    $_SESSION["id"] = $id;
                    header("Location: esperandoaprobacion.html");
                }else{
                    json_encode(["message" => "El usuario ya existe"]);
                }
            }elseif($_SESSION["id"] != "0"){
                $result = $con->checkApproved($id);
                if($result == 0){
                    header("Location: esperandoaprobacion.html");
                }elseif($result == 1){
                    header("Location: usuarios.html");
                }else{
                    echo json_encode(["message" => "Error"]);
                }
            }else{
                echo json_encode(["message" => "Error en el registro"]);
            }
        }else{
            echo json_encode(["message" => "Error en el registro"]);
        }
        
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}





?>