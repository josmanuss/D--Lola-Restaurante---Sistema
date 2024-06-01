<?php 

class CargoModel{
    protected $db;
    protected $cargos;

    public function __construct(){
        $this->db = Conexion::Conexion();
        $this->cargos = array();
    }

    public function getCargo(): array {
        $this->db = Conexion::Conexion();
        $prepCar = $this->db->prepare("SELECT * FROM Cargo");
        $prepCar->execute();
        $resultado = $prepCar->get_result();
        if ( $resultado->num_rows > 0){
            while ( $row = $resultado->fetch_assoc()){
                $this->cargos[] = $row;
            }
        }
        $resultado->close();
        $prepCar->close();
        return $this->cargos;
    }
    public function getCargoID($id){
        $this->db = Conexion::Conexion();
        $cargosID = null;
        $prepCarID = $this->db->prepare("SELECT * FROM Cargo WHERE iCarID = ?");
        $prepCarID->bind_param("i", $id);
        $prepCarID->execute();
        $resultadoID = $prepCarID->get_result();
        if ( $resultadoID->num_rows > 0){
            while ( $row = $resultadoID->fetch_array(MYSQLI_NUM)){
                $cargosID = $row;
            }
        }
        $resultadoID->close();
        $prepCarID->close();
        return $cargosID;
    }
    public function getCargoID1($id){
        $this->db = Conexion::Conexion();
        $cargosID = array();
        $prepCarID = $this->db->prepare("SELECT * FROM Cargo WHERE iCarID = ?");
        $prepCarID->bind_param("i", $id);
        $prepCarID->execute();
        $resultadoID = $prepCarID->get_result();
        if ( $resultadoID->num_rows > 0){
            while ( $row = $resultadoID->fetch_assoc()){
                $cargosID[] = $row;
            }
        }
        $resultadoID->close();
        $prepCarID->close();
        return $cargosID;
    }
    public function save($data){
        $this->db = Conexion::Conexion();
        $saveCar = $this->db->prepare("INSERT INTO Cargo (tCarNombre) VALUES(?);");
        $saveCar->bind_param("s", $data);
        $saveCar->execute();
        $success = $saveCar->affected_rows > 0;
        $saveCar->close();
        return $success;
    }

    public function update($data){
        $this->db = Conexion::Conexion();
        $updCar = $this->db->prepare("UPDATE cargo SET tCarNombre = ? WHERE iCarID = ?");
        $updCar->bind_param("si", $data['nombre'],$data['id']);
        $updCar->execute();
        $success = $updCar->affected_rows > 0;
        $updCar->close();
        return $success;
    }
    public function delete($id){
        $this->db = Conexion::Conexion();
        $updCar = $this->db->prepare("DELETE FROM cargo WHERE iCarID = ?");
        $updCar->bind_param("i", $id);
        $updCar->execute();
        $success = $updCar->affected_rows > 0;
        $updCar->close();
        return $success;
    }
}