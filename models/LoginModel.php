<?php

class LoginModel{
    public $db;

    public function __construct(){
        $this->db = Conexion::Conexion();
    }

    public function validarLogueo($data): bool {

            $correo = $data["correo"];
            $clave = $data["clave"];
            $stmt = $this->db->prepare("CALL IniciarSesionTrabajador(?, ?)");
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->db->error);
            }
            $stmt->bind_param("ss", $correo, $clave);
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }            
            $resultado = $stmt->get_result();
            $success = $resultado->num_rows > 0;
            $stmt->close();   
            return $success;

    }
    
    public function usuarioActivo($data): void {
        $correo = $data["correo"];
        $clave = hash("SHA256",$data["clave"]);
        $stmt = $this->db->prepare("CALL actualizarUsuarioActivo(?, ?)");
        $stmt->bind_param("ss", $correo, $clave);
        $stmt->execute();
        $stmt->close();
    }
    
    public function desactivarUsuario($data): void {
        $correo = $data["correo"];
        $clave = $data["clave"];
        $stmt = $this->db->prepare("CALL desactivarUsuario(?, ?)");
        $stmt->bind_param("ss",$correo,$clave);
        $stmt->execute();
        $stmt->close();
    }

    public function validarDatosSesion($data): ?array {
        $correo = $data["correo"];
        $clave = hash("SHA256",$data["clave"]);
        // $clave = $data["clave"];
        $this->db = Conexion::Conexion();
        if ($this->db->connect_error) {
            die("Error de conexión a la base de datos: " . $this->db->connect_error);
        }
        $consulta = $this->db->prepare("CALL IniciarSesionTrabajador(?, ?)");
        if (!$consulta) {
            die("Error al preparar la consulta: " . $this->db->error);
        }

        $consulta->bind_param("ss", $correo, $clave);
        if (!$consulta->execute()) {
            die("Error al ejecutar la consulta: " . $consulta->error);
        }
        $resultado = $consulta->get_result();
        $usuario = $resultado->fetch_assoc();
        $consulta->close();
        return isset($usuario) ? $usuario : null;
    }
    

}