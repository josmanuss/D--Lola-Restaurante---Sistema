<?php 
    require "models/TipoDocumentoModel.php";
    class ClienteModel extends TipoDocumentoModel{
        protected $db;
        protected $Cliente;
        protected $ClienteID;

        public function __construct()
        {
            $this->db = Conexion::Conexion();
            $this->Cliente = array();
        }
        
        public function getCliente()
        {
            $stmt = $this->db->prepare("SELECT * FROM `recuperarclientes`");
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $this->Cliente[] = $row;
                }
            }
            $stmt->close();
            return $this->Cliente;
        }

        public function clientesDNI()
        {
            $dni = array();
            $stmt = $this->db->prepare("SELECT tPerNumDoc AS DNI FROM `recuperarclientes`");
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $dni[] = $row;
                }
            }
            $stmt->close();
            return $dni;
        }
        
        public function getClienteDNI($dni)
        {
            $cliente = array();
            $stmt = $this->db->prepare("SELECT rc.cCliID AS ClienteID, CONCAT(rc.cPerNombre, ' ', rc.cPerApellidos) AS NombreCompleto FROM recuperarclientes rc WHERE rc.tPerNumDoc = ?");
            $stmt->bind_param("s",$dni);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ( $resultado->num_rows > 0 ){
                while ( $row = $resultado->fetch_array()){
                    $cliente = $row;
                }
            }
            $resultado->close();
            $stmt->close();
            return $cliente;
        }
        public function getClienteID($id)
        {

            $stmt = $this->db->prepare("CALL RecuperarClienteID(?)");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $this->ClienteID = null;
            if ($resultado->num_rows > 0) {
                while ($row = $resultado->fetch_assoc()) {
                    $this->ClienteID = $row;
                }
            }
            $stmt->close();
            $resultado->close();
            return $this->ClienteID;
        }
        
        public function save($data)
        {
            $stmt = $this->db->prepare("CALL RegistrarCliente(?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssissss", 
                        $data["nombre"], 
                        $data["apellido"],
                        $data["fecha_nac"],
                        $data["tipodocumento"],
                        $data["numerodoc"],
                        $data["correo"],
                        $data["genero"],
                        $data["pais"]
            );
            $stmt->execute();
            $success = $stmt->affected_rows > 0;
            $stmt->close();
            return $success;
        }
        public function updateUser($data)
        {
            $stmt = $this->db->prepare("CALL ActualizarCliente(?,?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("issdissssi", 
                            $data["idpersona"],
                            $data["nombre"], 
                            $data["apellido"],
                            $data["fecha_nac"],
                            $data["tipodocumento"],
                            $data["numerodoc"],
                            $data["correo"],
                            $data["genero"],
                            $data["pais"],
                            $data["habilitado"]
            );
            $stmt->execute();
            if ($stmt->error) {
                echo "Error en la ejecución del procedimiento almacenado: " . $stmt->error;
            }
            $success = $stmt->affected_rows > 0;

            $stmt->close();
            return $success;
        }

        public function validateCustomerType($id){
            if (isset($id) && $id === "0") {
                try {
                    $conexion = Conexion::Conexion();
                    $result = $conexion->query("SELECT cCliID FROM cliente WHERE cCliTipoCliente = 'CLIENTE EN RESTAURANTE' LIMIT 1");
                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $customerId = $row['cCliID'];
                    } else {
                        $customerId = null; 
                    }
                } catch (Exception $e) {
                    $customerId = null;
                }
                return $customerId;
            }
        }
        


        public function deleteCustomer($idPersona) : bool
        {   
            $sql = "DELETE * FROM cliente WHERE cCliID = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i",$idPersona);
            $success = $stmt->execute();
            $stmt->close();
            return $success;
        }

    }
?> 