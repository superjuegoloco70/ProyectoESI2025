<?php
include "empezarconexion.php";

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
                $query = "Select Contraseña from usuarios where CI=$id";
                $result = $con->query($query);
                $data = $result->fetch_assoc();
                if($passwd == $data["Contraseña"]){
                    echo json_encode(["message" => "Sesión iniciada"]);
                }else{
                    echo json_encode(["message" => "Error en el inicio de sesión"]);
                } 
            }else{
                echo json_encode(["message" => "Error en el inicio de sesión"]);
            }
        }else{
            echo json_encode(["message" => "Error en el inicio de sesión"]);
        }
        break;
     case 'POST':
        //Registro Usuario
        if(isset($_POST["Ci"]) && isset($_POST["passwd"] ) && isset($_POST["name"])){
            $name = $_POST['name'];
            $id = $_POST['Ci'];
            $passwd = $_POST['passwd'];
            if($id != null && $passwd != null && $name != null){
                $query = "Select * from usuarios where CI=$id";
                $result = $con->query($query);
                $data = $result->fetch_assoc();
                if($data["CI"] != $id){
                    $query = "INSERT INTO usuarios (CI, Nombre, Contraseña, Estado) VALUES ('$id', '$name', '$passwd', 'N/A')";
                    $con->query($query);
                    echo json_encode(["message" => "usuario Registrado"]);
                }else{
                    echo json_encode(["message" => "El usuario ya existe"]);
                }
                
            }else{
                echo json_encode(["message" => "Error en el registro"]);
            }
        }else{
            echo json_encode(["message" => "Error en el registro"]);
        }
        
        break;

    case 'PUT':
        $id = $_GET['id'];
        $socio = $_GET['socio'];
        $con->query("UPDATE usuarios SET EsSocio='$socio' WHERE CI=$id");
        echo json_encode(["message" => "User updated successfully"]);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $con->query("DELETE FROM usuarios WHERE CI=$id");
        echo json_encode(["message" => "User deleted successfully"]);
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}





?>