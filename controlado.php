<?php

    //Versión 0.1.2

    include "registro_api.php";

    $api = new api();



    $api->registrarUsuario($_POST["Ci"], $_POST["name"], $_POST["passwd"]);




?>