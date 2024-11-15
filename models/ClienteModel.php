<?php 
    require "models/TipoDocumentoModel.php";
    class ClienteModel extends TipoDocumentoModel{
        public $db;
        public $Cliente;
        public $ClienteID;

        
        public function __construct()
        {
            $this->db = Conexion::ConexionSQL();
            $this->Cliente = array();
        }
        
        public function getCliente()
        {
            try {
                $sql = "SELECT * FROM recuperarclientes";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                $this->Cliente = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                
                return $this->Cliente;
            } catch (PDOException $e) {
                return null;
            }
        }
        
        public function clientesDNI()
        {
            try {
                $sql = "SELECT tPerNumDoc AS DNI FROM recuperarclientes";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                $dni = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                return $dni;
            } catch (PDOException $e) {
                return [];
            }
        }
        
        public function getClienteDNI($dni)
        {
            try {
                $sql = "SELECT c.cCliID AS ClienteID, CONCAT(p.cPerNombre, ' ', p.cPerApellidos) AS NombreCompleto 
                        FROM cliente c 
                        INNER JOIN persona p ON c.cPerID = p.cPerID 
                        WHERE p.tPerNumDoc = :documento";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':documento', $dni, PDO::PARAM_STR);
                $stmt->execute();
                $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                return $cliente;
            } catch (PDOException $e) {
                return null;
            }
        }
        
        public function getClienteID($id)
        {
            try {
                $stmt = $this->db->prepare("CALL RecuperarClienteID(:_idCliente)");
                $stmt->bindParam(':_idCliente', $id, PDO::PARAM_INT);
                $stmt->execute();
                $this->ClienteID = $stmt->fetch(PDO::FETCH_ASSOC);
                $stmt->closeCursor();
                return $this->ClienteID;
            } catch (PDOException $e) {
                return null;
            }
        }
                
        public function save($data)
        {
            try {
                $this->db->beginTransaction();
                $stmt = $this->db->prepare("CALL RegistrarCliente(:_nombre, :_apellidos, :_edad, :_idTipoDoc, :_numDoc, :_correo, :_genero, :_pais)");
                $stmt->bindParam(':_nombre', $data["nombre"], PDO::PARAM_STR);
                $stmt->bindParam(':_apellidos', $data["apellido"], PDO::PARAM_STR);
                $stmt->bindParam(':_edad', $data["fecha_nac"], PDO::PARAM_STR);
                $stmt->bindParam(':_idTipoDoc', $data["tipodocumento"], PDO::PARAM_INT);
                $stmt->bindParam(':_numDoc', $data["numerodoc"], PDO::PARAM_STR);
                $stmt->bindParam(':_correo', $data["correo"], PDO::PARAM_STR);
                $stmt->bindParam(':_genero', $data["genero"], PDO::PARAM_STR);
                $stmt->bindParam(':_pais', $data["pais"], PDO::PARAM_STR);
                $stmt->execute();
                $success = $stmt->rowCount() > 0;
                $this->db->commit();
                $stmt->closeCursor();
                return $success;
            } catch (PDOException $e) {
                $this->db->rollBack();
                throw $e;
            }
        }
        
        public function updateUser($data)
        {
            try {
                $this->db->beginTransaction();
                $stmt = $this->db->prepare("CALL ActualizarCliente(:_idPersona, :_nombre, :_apellidos, :_edad, :_idTipoDoc, :_numDoc, :_correo, :_genero, :_pais, :_habilitado)");
                $stmt->bindParam(':_idPersona', $data["idpersona"], PDO::PARAM_INT);
                $stmt->bindParam(':_nombre', $data["nombre"], PDO::PARAM_STR);
                $stmt->bindParam(':_apellidos', $data["apellido"], PDO::PARAM_STR);
                $stmt->bindParam(':_edad', $data["fecha_nac"], PDO::PARAM_STR);
                $stmt->bindParam(':_idTipoDoc', $data["tipodocumento"], PDO::PARAM_INT);
                $stmt->bindParam(':_numDoc', $data["numerodoc"], PDO::PARAM_STR);
                $stmt->bindParam(':_correo', $data["correo"], PDO::PARAM_STR);
                $stmt->bindParam(':_genero', $data["genero"], PDO::PARAM_STR);
                $stmt->bindParam(':_pais', $data["pais"], PDO::PARAM_STR);
                $stmt->bindParam(':_habilitado', $data["habilitado"], PDO::PARAM_BOOL);
                $stmt->execute();
                $success = $stmt->rowCount() > 0;
                $this->db->commit();
                $stmt->closeCursor();
                return $success;
            } catch (PDOException $e) {
                $this->db->rollBack();
                throw $e;
            }
        }
        
        public function validateCustomerType($id) {
            if (isset($id) && $id === "0") {
                try {
                    $sql = "SELECT cCliID FROM cliente WHERE cCliTipoCliente = 'CLIENTE EN RESTAURANTE' LIMIT 1";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $customerId = $row ? $row['cCliID'] : null;
                    return $customerId;
                } catch (PDOException $e) {
                    return null;
                }
            }
            return null; 
        }

        public function deleteCustomer($id): bool {
            try {
                $sql = "DELETE FROM cliente WHERE cPerID = :cPerID";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':cPerID', $id, PDO::PARAM_INT);
                $success = $stmt->execute();
                return $success;
            } catch (PDOException $e) {
                return false;
            }
        }
    }
?> 