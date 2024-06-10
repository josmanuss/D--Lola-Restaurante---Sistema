<?php
class PlatoModel{
    public $db;
    public $platos;
    public function __construct(){
        $this->db = Conexion::Conexion();
        $this->platos = array();
    }

    public function getPlato(){
        $consulta = $this->db->prepare("SELECT cPlaID, cCatID, cPlaNombre, cPlaPrecio FROM platos");
        $consulta->execute();
        $resultado = $consulta->get_result();
        if ( $resultado->num_rows > 0){
            while ( $row = $resultado->fetch_assoc()){
                $this->platos[] = $row;
            }
        }
        $consulta->close();
        $resultado->close();
        return $this->platos;
    }
    public function getPlatoNombrePrecio(){
        $platos = array();
        $consulta = $this->db->prepare("SELECT cPlaNombre, cPlaPrecio FROM platos");
        $consulta->execute();
        $resultado = $consulta->get_result();
        if ( $resultado->num_rows > 0){
            while ( $row = $resultado->fetch_array()){
                $platos[] = $row;
            }
        }
        $consulta->close();
        $resultado->close();
        return $platos;
    }
    public function getPlatoPorNombrePrecio($data){
        $platos = array();
        $consulta = $this->db->prepare("SELECT cPlaID, cCatID, cPlaNombre, cPlaPrecio FROM platos WHERE cPlaNombre = ? AND cPlaPrecio = ?");
        $consulta->bind_param("sd",$data["nombre"],$data["precio"]);
        $consulta->execute();
        $resultado = $consulta->get_result();
        if ( $resultado->num_rows > 0){
            while ( $row = $resultado->fetch_array(MYSQLI_NUM)){
                $platos[] = $row;
            }
        }
        $consulta->close();
        $resultado->close();
        return $platos;
    }

    public function save($datos){

    }

    public function update($datos){

    }
    public function delete($datos){
        $conn = Conexion::Conexion();
        $stmt = $conn->prepare("DELETE platos WHERE cPlaID = ?");
        $stmt->bind_param("i", $datos["idEliminar"]);
        $stmt->execute();
    }
}

