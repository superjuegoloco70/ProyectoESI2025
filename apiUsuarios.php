<?php
include "conexion.php";

header("Content-Type: application/json");
$method = $_SERVER["REQUEST_METHOD"];
$input = json_decode(file_get_contents("php://input"), true);


$con = new db();

switch ($method){
    case "GET":
        //Iniciar Sesión
        if(isset($_GET["Ci"]) && isset($_GET["passwd"])){
            $id = $_GET["Ci"];
            $passwd=$_GET["passwd"];
            if($id != null && $passwd != null){
                $data = $con->checkCiPass($id, $passwd);
                if($passwd == $data["Contrasena"]){
                    echo json_encode(["message" => "Sesion iniciada"]);
                }else{
                    echo json_encode(["message" => "Error en el inicio de sesion"]);
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
        if(isset($_POST["Ci"]) && isset($_POST["passwd"] ) && isset($_POST["name"])){
            $name = $_POST['name'];
            $id = $_POST['Ci'];
            $passwd = $_POST['passwd'];
            if($id != null && $passwd != null && $name != null){
                $result = $con->newUser($name, $id, $passwd);
                echo $result;
                
            }else{
                echo json_encode(["message" => "Error en el registro"]);
            }
        }else{
            echo json_encode(["message" => "Error en el registro"]);
        }
        
        break;

    /*case 'PUT':
        $id = $_GET['id'];
        $socio = $_GET['socio'];
        $con->query("UPDATE usuarios SET EsSocio='$socio' WHERE CI=$id");
        echo json_encode(["message" => "User updated successfully"]);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $con->query("DELETE FROM usuarios WHERE CI=$id");
        echo json_encode(["message" => "User deleted successfully"]);
        break;*/

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}





?>