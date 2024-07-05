<?php
class PlatoModel{
    public $db;
    public $platos;
    public $platoID;
    public function __construct(){
        $this->db = Conexion::ConexionSQL();
        $this->platos = array();
        $this->platoID = array();
    }

    public function getPlato(){
        $consulta = $this->db->prepare("SELECT cPlaID, cCatID, cPlaNombre, cPlaPrecio, cPlaCantidad, cPlaDescripcion FROM platos");
        $consulta->execute();
        $this->platos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = null;
        return $this->platos;
    }

    public function getPlatoID($cPlaID){
        $consulta = $this->db->prepare("SELECT cPlaID,cPlaImagen, cCatID, cPlaNombre, cPlaPrecio, cPlaCantidad, cPlaDescripcion FROM platos WHERE cPlaID = :cPlaID");
        $consulta->bindParam(':cPlaID', $cPlaID, PDO::PARAM_INT);
        $consulta->execute();
        $this->platoID = $consulta->fetchAll(PDO::FETCH_ASSOC);
        foreach ($this->platoID as &$plato){
            $plato["cPlaImagen"] = base64_encode($plato["cPlaImagen"]);
        }
        $consulta = null;
        return $this->platoID;
    }
    


    public function getPlatoNombrePrecio(){
        $consulta = $this->db->prepare("SELECT cPlaNombre, cPlaPrecio FROM platos");
        $consulta->execute();
        $platos = $consulta->fetchAll(PDO::FETCH_ASSOC);
        $consulta = null;
        return $platos;
    }
    public function getPlatoPorNombrePrecio($data){
        $consulta = $this->db->prepare("SELECT cPlaID, cCatID, cPlaNombre, cPlaPrecio FROM platos WHERE cPlaNombre = :cPlaNombre AND cPlaPrecio = :cPlaPrecio");
        if(isset($data["nombre"]) && isset($data["precio"])) {
            $consulta->bindParam(":cPlaNombre", $data["nombre"], PDO::PARAM_STR);
            $consulta->bindParam(":cPlaPrecio", $data["precio"], PDO::PARAM_STR);
            $consulta->execute();
            $platos = $consulta->fetchAll(PDO::FETCH_ASSOC);  
            return $platos;
        } else {
            return null; 
        }
    }    
    
    public function save($plato){
        $consulta = $this->db->prepare(
            "INSERT INTO platos (cCatID, cPlaImagen ,cPlaNombre, cPlaPrecio, cPlaCantidad, cPlaDescripcion) 
            VALUES (:categoriaPlato, :imagen ,:txtNombres, :spinnerPrecio, :spinnerCantidad, :txtDescripcion)"
        );
        $consulta->bindParam(':categoriaPlato', $plato['categoriaPlato'], PDO::PARAM_STR);
        $consulta->bindParam(':imagen', $plato['imagen'], PDO::PARAM_LOB);
        $consulta->bindParam(':txtNombres', $plato['txtNombres'], PDO::PARAM_STR);
        $consulta->bindParam(':spinnerPrecio', $plato['spinnerPrecio'], PDO::PARAM_STR);
        $consulta->bindParam(':spinnerCantidad', $plato['spinnerCantidad'], PDO::PARAM_INT);
        $consulta->bindParam(':txtDescripcion', $plato['txtDescripcion'], PDO::PARAM_STR);
        return $consulta->execute();
    }

    public function updateProduct($plato) {
        $consulta = $this->db->prepare(
            "UPDATE platos SET cCatID = :categoriaPlato,cPlaImagen = :imagen, cPlaNombre = :txtNombres, cPlaPrecio = 
            :spinnerPrecio, cPlaCantidad = :spinnerCantidad, cPlaDescripcion = :txtDescripcion 
            WHERE cPlaID = :idPlato"
        );
        $consulta->bindParam(':categoriaPlato', $plato['categoriaPlato'], PDO::PARAM_STR);
        $consulta->bindParam(':imagen', $plato['imagen'], PDO::PARAM_LOB);
        $consulta->bindParam(':txtNombres', $plato['txtNombres'], PDO::PARAM_STR);
        $consulta->bindParam(':spinnerPrecio', $plato['spinnerPrecio'], PDO::PARAM_STR);
        $consulta->bindParam(':spinnerCantidad', $plato['spinnerCantidad'], PDO::PARAM_INT);
        $consulta->bindParam(':txtDescripcion', $plato['txtDescripcion'], PDO::PARAM_STR);
        $consulta->bindParam(':idPlato', $plato['idPlato'], PDO::PARAM_INT);
        return $consulta->execute();
    }
    
    public function delete($datos){
        $stmt = $this->db->prepare("DELETE platos WHERE cPlaID = :cPlaID");
        $stmt->bindParam(":cPlaID", $datos["idEliminar"],PDO::PARAM_INT);
        $stmt->execute();
    }
}

