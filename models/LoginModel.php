<?php

class LoginModel {
    public $db;

    public function __construct() {
        $this->db = Conexion::ConexionSQL();
    }

    public function usuarioActivo($data): void {
        $correo = $data["correo"];
        $clave = hash("SHA256", $data["clave"]);
        $stmt = $this->db->prepare("CALL ActualizarUsuarioActivo(:p_Mandar, :p_Clave)");
        $stmt->bindParam(":p_Mandar", $correo, PDO::PARAM_STR);
        $stmt->bindParam(":p_Clave", $clave, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function desactivarUsuario($data): void {
        $correo = $data["correo"];
        $clave = $data["clave"];
        $stmt = $this->db->prepare("CALL DesactivarUsuario(:p_Mandar, :p_Clave)");
        $stmt->bindParam(":p_Mandar", $correo, PDO::PARAM_STR);
        $stmt->bindParam(":p_Clave", $clave, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function validarDatosSesion($data): ?array {
        $correo = $data["correo"];
        $clave = hash("SHA256", $data["clave"]);
        $stmt = $this->db->prepare("CALL IniciarSesionTrabajador(:p_Mandar, :p_Clave)");
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->db->errorInfo()[2]);
        }
        $stmt->bindParam(":p_Mandar", $correo, PDO::PARAM_STR);
        $stmt->bindParam(":p_Clave", $clave, PDO::PARAM_STR);
        $stmt->execute();        
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $usuario ?: null;
    }
}
