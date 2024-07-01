<?php
    require "models/CargoModel.php";
    require "models/TipoDocumentoModel.php";
    class TrabajadorModel extends TipoDocumentoModel{
        protected $trabajador;
        protected $cargo;
        public function __construct(){
            $this->trabajador = array();
            $this->cargo = new CargoModel();
        }
        public function getTrabajador(){
            $conn = Conexion::Conexion();
            $stmt = $conn->prepare("SELECT * FROM usuariostrabajadores");
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $this->trabajador[] = $row;
                }
            }
            $result->close();
            return $this->trabajador;
        }
        

        public function getTrabajadorID($id){
            $conn = Conexion::Conexion();
            $sql = $conn->prepare("CALL RecuperarTrabajadorPorID(?)");
            $sql->bind_param("i",$id);
            $sql->execute();
            $resultado = $sql->get_result();
            $trabajador = $resultado->fetch_assoc();
            $sql->close();
            $conn->close();
            return $trabajador;
        }

        public function getCargo(): array{
            return $this->cargo->getCargo();
        }
        public function save($data) : bool{
            $conn = Conexion::Conexion();
            $sql = $conn->prepare("CALL RegistrarTrabajador(?,?,?,?,?,?,?,?,?,?,?,?);");
            $sql->bind_param("sssissssissd", $data["nombre"], $data["apellido"], $data["fecha_nac"],
                            $data["tipodocumento"],$data["numerodoc"],$data["correo"],$data["genero"],$data["pais"],$data["cargo"],
                            $data["n_usuario"],$data["clave"],$data["sueldo"]);
            $sql->execute();
            $success = $sql->affected_rows > 0;
            $sql->close();
            $conn->close();
            return $success;
        }

        public function updateWorker($data) : bool{
            $conn = Conexion::Conexion();
            $success = false;
            $sql = $conn->prepare("CALL ActualizarTrabajador(?,?,?,?,?,?,?,?,?,?,?,?,?);");
            $sql->bind_param("isssissssissd", $data["idpersona"], $data["nombre"], $data["apellido"], $data["fecha_nac"],
                            $data["tipodocumento"], $data["numerodoc"], $data["correo"], $data["genero"], $data["pais"], $data["cargo"],
                            $data["n_usuario"], $data["clave"], $data["sueldo"]);
            $sql->execute();
            $success = $sql->affected_rows > 0;
            $sql->close();
            $conn->close();
            return $success;
        }
        
    
        public function delete($data) : bool{
            $success = false;
            $stmt = null;
            try {
                if (!is_numeric($data) || $data <= 0) {
                    throw new Exception("El ID de la persona no es válido.");
                }
                $stmt = $this->db->prepare("DELETE FROM persona WHERE cPerID = ?");
                $stmt->bind_param("i", $data);
                $stmt->execute();
                if ($stmt->affected_rows > 0) {
                    $success = true;
                } else {
                    throw new Exception("No se pudo eliminar la persona.");
                }
            } catch (Exception $e) {
                error_log("Error al eliminar persona: " . $e->getMessage());
                $success = false;
            } finally{
                $stmt->close();
            }
            return $success;
        }
        

        public function reportWorker(){
            $conn = Conexion::Conexion();
            $trabajadores = array();
            $stmt = $conn->prepare("SELECT * FROM `vw_trabajadores_cargo`");
            $stmt->execute();
            $result = $stmt->get_result();
            if ( $result->num_rows >0){
                while($row = $result->fetch_assoc()){
                    $trabajadores[] = $row;
                }
            }
            $result->close();
            $stmt->close();
            $conn->close();
            return $trabajadores;
        }

    }
?>