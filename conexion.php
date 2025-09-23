<?php

    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }

    class db{

        public $conn;
        public function __construct() {
            $this->conn = new mysqli("localhost:3306","root","","3mhdr");
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
        }

        public function checkCiPass($id){
            $query = "Select Contrasena from usuarios where CI=$id";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            return $data;
        }

        public function checkApproved($id){
            $res = "";
            $query = "Select Estado from usuarios where CI=$id";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            return $data["Estado"];
        }

        public function newUser($name, $id, $passwd){
            $query = "Select * from usuarios where CI=$id";
            $result = $this->conn->query($query);
            $data = $result->fetch_assoc();
            if(!$data){
                $query = "INSERT INTO usuarios (CI, Nombre, Contrasena, Estado) VALUES ('$id', '$name', '$passwd', 0)";
                $this->conn->query($query);
                $res = true;
            }else{
                $res = false;
            }
            return $res;
        }

        public function registerHours($fecha, $horas){
            $query = "INSERT INTO horastrabajadas (FechaTrabajo, N_Horas, CI_Trabajador) VALUES ('$fecha', '$horas', '$_SESSION[id]')";
            $this->conn->query($query); 
        }

        public function getWaiting(){
            $query = "SELECT * FROM usuarios WHERE Estado = 0";
            $result = $this->conn->query($query);
            $json = [];
            while($row = mysqli_fetch_assoc($result)){
                $json[] = $row;
            }
            
            return $json;
        }

        public function aprobarUsuario($ci){
            $query = "UPDATE usuarios set Estado = 1 WHERE CI = '$ci'";
            $res = false;
            if($this->conn->query($query)){
                $res = true;
            }else{
                $res = false;
            }

            return $res;

        }

        public function agregarPago($ci, $coste, $fecha, $tipo){
            $query = "INSERT INTO cuotas (CI_Prestarario, Coste, Vencimiento, TipodeCuota) VALUES ('$ci', '$coste', '$fecha', '$tipo')";
            $this->conn->query($query);
        }

        public function getPagosVacios($ci){
            $query = "SELECT * FROM cuotas WHERE CI_Prestarario = '$ci' AND ConfirmantePago IS NULL";
            $result = $this->conn->query($query);
            $json = [];
            while($row = mysqli_fetch_assoc($result)){
                $json[] = $row;
            }

            return $json;
        }

        public function agregarComprobantePago($id, $comprobante){
            $query = "UPDATE cuotas SET ConfirmantePago = ? WHERE ID_Cuota = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                echo json_encode(["status" => "error", "mensaje" => "Error al preparar la consulta: " . $this->conn->error]);
                exit;
            }

            
            $null = NULL;
            $stmt->bind_param("bi", $null, $id);
            $stmt->send_long_data(0, $comprobante);
            $res = $stmt->execute();
            $stmt->close();
            return $res;

        }

        public function getPagosComprobante(){
            $query = "SELECT * FROM cuotas WHERE PagoAprobado = 0 AND ConfirmantePago IS NOT NULL";
            $stmt = $this->conn->prepare($query);
            $json = [];
            if($stmt->execute()){
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    if (!is_null($row['ConfirmantePago'])) {
                        $row['ConfirmantePago'] = base64_encode($row['ConfirmantePago']);
                    }
                    $json[] = $row;
                }
            }
            if(count($json) == 0){
                return [
                    "status" => "success",
                    "message" => "No hay pagos pendientes."
                ];
            }

            return [
                "status" => "success",
                "message" => $json
            ];
        }

        public function getPagoAprobado($ci){
            $query = "SELECT * FROM cuotas WHERE PagoAprobado = 1 AND ConfirmantePago IS NOT NULL AND CI_Prestarario = '$ci'";
            $stmt = $this->conn->prepare($query);
            $json = [];
            if($stmt->execute()){
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    if (!is_null($row['ConfirmantePago'])) {
                        $row['ConfirmantePago'] = base64_encode($row['ConfirmantePago']);
                    }
                    $json[] = $row;
                }
            }
            if(count($json) == 0){
                return [
                    "status" => "success",
                    "message" => "No hay pagos pendientes."
                ];
            }

            return [
                "status" => "success",
                "message" => $json
            ];
        }

        public function getPagosPorAprobar($ci){
            $query = "SELECT * FROM cuotas WHERE PagoAprobado = 0 AND ConfirmantePago IS NOT NULL AND CI_Prestarario = '$ci'";
            $stmt = $this->conn->prepare($query);
            $json = [];
            if($stmt->execute()){
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()) {
                    if (!is_null($row['ConfirmantePago'])) {
                        $row['ConfirmantePago'] = base64_encode($row['ConfirmantePago']);
                    }
                    $json[] = $row;
                }
            }
            if(count($json) == 0){
                return [
                    "status" => "success",
                    "message" => "No hay pagos pendientes."
                ];
            }

            return [
                "status" => "success",
                "message" => $json
            ];
        }

        public function aprobarComprobantePago($id){
            $query = "UPDATE cuotas SET PagoAprobado = ? WHERE ID_Cuota = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                echo json_encode(["status" => "error", "mensaje" => "Error al preparar la consulta: " . $this->conn->error]);
                exit;
            }

            $pagoAprobado = 1;
            $stmt->bind_param("ii", $pagoAprobado, $id);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }

        public function denegarComprobantePago($id){
            $query = "UPDATE cuotas SET PagoAprobado = ?, ConfirmantePago = NULL WHERE ID_Cuota = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                echo json_encode(["status" => "error", "mensaje" => "Error al preparar la consulta: " . $this->conn->error]);
                exit;
            }

            $pagoAprobado = 0;
            $stmt->bind_param("ii", $pagoAprobado, $id);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }

        public function getHoras($ci){
            $query = "SELECT * FROM horastrabajadas WHERE CI_Trabajador = $ci";
            $result = $this->conn->query($query);
            $json = [];
            while($row = mysqli_fetch_assoc($result)){
                $json[] = $row;
            }
            
            return $json;
        }

    }
    

?>