<?php
include "empezarconexion.php";

header("Content-Type: application/json");
$method = $_SERVER["REQUEST_METHOD"];
$input = json_decode(file_get_contents("php://input"), true);

switch ($method){
    case "GET":
        if(isset($_GET["id"])){
            $id = $_GET["id"];
            $result = $conn->query("Select * from usuarios where CI=$id");
            $date = $result->fetch_assoc();
            echo json_encode($data);
        }else{
            $result = $conn->query("Select * from usuarios");
            $users = [];
            while ($row = $result->fetch_assoc()){
                $users[] = $row;
            }
            echo json_encode($users);
        }
        break;
     case 'POST':
        $name = $input['name'];
        $id = $input['id'];
        $passwd = $input['passwd'];
        $conn->query("INSERT INTO usuarios (CI, Nombre, Contraseña) VALUES ('$id', '$name', $passwd)");
        echo json_encode(["message" => "User added successfully"]);
        break;

    case 'PUT':
        $id = $_GET['id'];
        $socio = $input['socio'];
        $conn->query("UPDATE usuarios SET EsSocio='$socio', WHERE CI=$id");
        echo json_encode(["message" => "User updated successfully"]);
        break;

    case 'DELETE':
        $id = $_GET['id'];
        $conn->query("DELETE FROM usuarios WHERE CI=$id");
        echo json_encode(["message" => "User deleted successfully"]);
        break;

    default:
        echo json_encode(["message" => "Invalid request method"]);
        break;
}

$conn->close();



?>