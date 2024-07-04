<?php
    require "models/CargoModel.php";
    require "models/TipoDocumentoModel.php";

    class TrabajadorModel extends TipoDocumentoModel {
        protected $trabajador;
        protected $cargo;

        public function __construct() {
            $this->trabajador = array();
            $this->cargo = new CargoModel();
        }

        public function getTrabajador(): array {
            $conn = Conexion::ConexionSQL();
            $stmt = $conn->prepare("SELECT * FROM usuariostrabajadores");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $this->trabajador = $result;
            }
            return $this->trabajador;
        }

        public function getTrabajadorID($id) {
            $conn = Conexion::ConexionSQL();
            $sql = $conn->prepare("CALL RecuperarTrabajadorPorID(:id)");
            $sql->bindParam(":id", $id, PDO::PARAM_INT);
            $sql->execute();
            $trabajador = $sql->fetch(PDO::FETCH_ASSOC);
            return $trabajador;
        }

        public function getCargo(): array {
            return $this->cargo->getCargo();
        }

        public function save($data): bool {
            $conn = Conexion::ConexionSQL();
            $sql = $conn->prepare("CALL RegistrarTrabajador(:_nombre, :_apellidos, :_edad, :_idTipoDoc, :_numDoc, :_correo, :_genero, :_pais, :_cargoID, :_usuario, :_clave, :_sueldo)");
            $sql->bindParam(':_nombre', $data["nombre"], PDO::PARAM_STR);
            $sql->bindParam(':_apellidos', $data["apellido"], PDO::PARAM_STR);
            $sql->bindParam(':_edad', $data["fecha_nac"], PDO::PARAM_STR);
            $sql->bindParam(':_idTipoDoc', $data["tipodocumento"], PDO::PARAM_INT);
            $sql->bindParam(':_numDoc', $data["numerodoc"], PDO::PARAM_STR);
            $sql->bindParam(':_correo', $data["correo"], PDO::PARAM_STR);
            $sql->bindParam(':_genero', $data["genero"], PDO::PARAM_STR);
            $sql->bindParam(':_pais', $data["pais"], PDO::PARAM_STR);
            $sql->bindParam(':_cargoID', $data["cargo"], PDO::PARAM_INT);
            $sql->bindParam(':_usuario', $data["n_usuario"], PDO::PARAM_STR);
            $sql->bindParam(':_clave', $data["clave"], PDO::PARAM_STR);
            $sql->bindParam(':_sueldo', $data["sueldo"], PDO::PARAM_STR);
            $sql->execute();
            $success = $sql->rowCount() > 0;
            return $success;
        }
        

        public function updateWorker($data): bool {
            $conn = Conexion::ConexionSQL();
            $sql = $conn->prepare("CALL ActualizarTrabajador(:_idPersona, :_nombre, :_apellidos, :_edad, :_idTipoDoc, :_numDoc, :_correo, :_genero, :_pais, :_cargoID, :_usuario, :_clave, :_sueldo)");
            $sql->bindParam(':_idPersona', $data["idpersona"], PDO::PARAM_INT);
            $sql->bindParam(':_nombre', $data["nombre"], PDO::PARAM_STR);
            $sql->bindParam(':_apellidos', $data["apellido"], PDO::PARAM_STR);
            $sql->bindParam(':_edad', $data["fecha_nac"], PDO::PARAM_STR);
            $sql->bindParam(':_idTipoDoc', $data["tipodocumento"], PDO::PARAM_INT);
            $sql->bindParam(':_numDoc', $data["numerodoc"], PDO::PARAM_STR);
            $sql->bindParam(':_correo', $data["correo"], PDO::PARAM_STR);
            $sql->bindParam(':_genero', $data["genero"], PDO::PARAM_STR);
            $sql->bindParam(':_pais', $data["pais"], PDO::PARAM_STR);
            $sql->bindParam(':_cargoID', $data["cargo"], PDO::PARAM_INT);
            $sql->bindParam(':_usuario', $data["n_usuario"], PDO::PARAM_STR);
            $sql->bindParam(':_clave', $data["clave"], PDO::PARAM_STR);
            $sql->bindParam(':_sueldo', $data["sueldo"], PDO::PARAM_STR);
            $sql->execute();
            $success = $sql->rowCount() > 0;
            return $success;
        }
        

        public function delete($data): bool {
            $conn = Conexion::ConexionSQL();
            $success = false;
            try {
                if (!is_numeric($data) || $data <= 0) {
                    throw new Exception("El ID de la persona no es válido.");
                }
                $stmt = $conn->prepare("DELETE FROM persona WHERE cPerID = :id");
                $stmt->bindParam(":id", $data, PDO::PARAM_INT);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    $success = true;
                } else {
                    throw new Exception("No se pudo eliminar la persona.");
                }
            } catch (Exception $e) {
                error_log("Error al eliminar persona: " . $e->getMessage());
                $success = false;
            }
            return $success;
        }
    }
?>
