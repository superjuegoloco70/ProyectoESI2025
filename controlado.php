<?php


    include "registro_api.php";

    $api = new api();

    if(isset($_POST["registro"])){
        if($_POST["Ci"] != null  && $_POST["name"] != null && $_POST["passwd"] != null){
            if($api->registrarUsuario($_POST["Ci"], $_POST["name"], $_POST["passwd"])){
                echo "Registrado Correctamente";
            }else{
                echo "El usuario ya existe";
            }
        }else{
            echo "Error en el ingreso de datos";
        }
    }elseif(isset($_POST["login"])){
        if($api->loginUsuario($_POST["Ci"], $_POST["passwd"])){
            echo "Login Correcto";
        }else{
            echo "Error en la CI o Contraseña";
        }
        
    }
    





?>