<?php 

class CargoModel{
    protected $db;
    protected $cargos;

    public function __construct(){
        $this->db = Conexion::ConexionSQL();
        $this->cargos = array();
    }

    public function getCargo(): array {
        $this->db = Conexion::ConexionSQL();
        $prepCar = $this->db->prepare("SELECT * FROM Cargo");
        $prepCar->execute();
        $this->cargos[] =  $prepCar->fetchAll(PDO::FETCH_ASSOC);
        $prepCar = null;
        return $this->cargos;
    }
    public function getCargoID($id) {
        $cargosID = null;
        $query = "SELECT * FROM Cargo WHERE iCarID = :iCarID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $cargosID = $row;
        }
        return $cargosID;
    }

    public function save($data) {
        $query = "INSERT INTO Cargo (tCarNombre) VALUES(:tCarNombre)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tCarNombre', $data, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function update($data) {
        $query = "UPDATE cargo SET tCarNombre = :tCarNombre WHERE iCarID = :iCarID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tCarNombre', $data['nombre'], PDO::PARAM_STR);
        $stmt->bindParam(':iCarID', $data['id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function delete($id) {
        $query = "DELETE FROM cargo WHERE iCarID = :iCarID";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':iCarID', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}