<?php
class PlatoModel{
    public $db;
    public $platos;
    public function __construct(){
        $this->db = Conexion::Conexion();
        $this->platos = array();
    }

    public function getPlato(){
        $this->db = Conexion::ConexionSQL();
        $consulta = $this->db->prepare("SELECT cPlaID, cCatID, cPlaNombre, cPlaPrecio, cPlaCantidad FROM platos");
        $consulta->execute();
        $this->platos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = null;
        return $this->platos;
    }
    public function getPlatoNombrePrecio(){
        $this->db = Conexion::ConexionSQL();
        $consulta = $this->db->prepare("SELECT cPlaNombre, cPlaPrecio FROM platos");
        $consulta->execute();
        $platos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = null;
        return $platos;
    }
    public function getPlatoPorNombrePrecio($data){
        $this->db = Conexion::ConexionSQL();
        $consulta = $this->db->prepare("SELECT cPlaID, cCatID, cPlaNombre, cPlaPrecio FROM platos WHERE cPlaNombre = :cPlaNombre AND cPlaPrecio = :cPlaPrecio");
        if(isset($data["nombre"]) && isset($data["precio"])) {
            $consulta->bindParam(":cPlaNombre", $data["nombre"], PDO::PARAM_STR);
            $consulta->bindParam(":cPlaPrecio", $data["precio"], PDO::PARAM_STR);
            $consulta->execute();
            $platos = $consulta->fetchAll(PDO::FETCH_ASSOC);  // Use fetchAll to get multiple rows
            return $platos;
        } else {
            return null; 
        }
    }    
    
    public function save($datos){

    }

    public function update($datos){

    }
    public function delete($datos){
        $this->db = Conexion::ConexionSQL();
        $stmt = $this->db->prepare("DELETE platos WHERE cPlaID = :cPlaID");
        $stmt->bindParam(":cPlaID", $datos["idEliminar"],PDO::PARAM_INT);
        $stmt->execute();
    }
}

