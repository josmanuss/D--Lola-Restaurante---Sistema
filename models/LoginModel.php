<?php

class LoginModel{
    public $db;

    public function __construct(){
        $this->db = Conexion::Conexion();
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
        $clave = hash("SHA256", $data["clave"]);
        $this->db = Conexion::Conexion();
        $consulta = $this->db->prepare("CALL IniciarSesionTrabajador(?, ?)");
        if (!$consulta) {
            throw new Exception("Error al preparar la consulta: " . $this->db->error);
        }
        $consulta->bind_param("ss", $correo, $clave);
        $consulta->execute();        
        $resultado = $consulta->get_result();
        $usuario = $resultado->fetch_assoc();
        $consulta->close();
        return $usuario ?: null;
    }

}