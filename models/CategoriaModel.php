<?php
class CategoriaModel {
    protected $db;
    protected $categorias;

    public function __construct() {
        $this->db = Conexion::ConexionSQL();
        $this->categorias = array();
    }

    public function getCategoria() {
        $stmt = $this->db->prepare("SELECT * FROM categoria");
        $stmt->execute();
        $this->categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($this->categorias as &$categoria) {
            $categoria["cCatImagen"] = base64_encode($categoria["cCatImagen"]);
        }
        return $this->categorias;
    }

    public function getCategoriaID($id_categoria) {
        $stmt = $this->db->prepare("SELECT * FROM categoria WHERE cCatID = ?");
        $stmt->execute([$id_categoria]);
        $categoriaEncontrada = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($categoriaEncontrada as &$categoria) {
            $categoria["cCatImagen"] = base64_encode($categoria["cCatImagen"]);
        }
        return $categoriaEncontrada;
    }

    public function idCategoria($data): int {
        $consulta = $this->db->prepare("SELECT cCatID FROM categoria WHERE cCatNombre = ?");
        $consulta->execute([$data["categoria"]]);
        $idEncontrado = $consulta->fetchColumn();
        return $idEncontrado ? (int) $idEncontrado : -1;
    }

    public function getPlatoIDCategoria($id){
        $sql = "SELECT p.cPlaID, p.cCatID, p.cPlaNombre, p.cPlaCantidad, p.cPlaPrecio FROM platos p INNER JOIN categoria c ON p.cCatID=c.cCatID 
                WHERE c.cCatID = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $platos = $stmt->fetchAll(PDO::FETCH_NUM);
        return $platos;
    }
    
    public function save($imagen, $nombre): bool {
        $sql = "INSERT INTO categoria (cCatImagen, cCatNombre) VALUES (:cCatImagen, :cCatNombre)";
        $saveCat = $this->db->prepare($sql);
        
        if ($imagen === null) {
            $saveCat->bindValue(':cCatImagen', null, PDO::PARAM_NULL);
        } else {
            $saveCat->bindParam(':cCatImagen', $imagen, PDO::PARAM_LOB);
        }
        $saveCat->bindParam(':nombre', $nombre, PDO::PARAM_STR);
        $saveCat->execute();
        $filasAfectadas = $saveCat->rowCount() > 0;
        return $filasAfectadas;
    }
    
    public function update($id, $imagen, $nombre): bool {
        $sql = "UPDATE categoria SET cCatImagen = :cCatImagen, cCatNombre = :nombre WHERE cCatID = :cCatID";
        $updateCat = $this->db->prepare($sql);
        $updateCat->bindParam(':cCatImagen', $imagen, PDO::PARAM_LOB);
        $updateCat->bindParam(':cCatNombre', $nombre, PDO::PARAM_STR);
        $updateCat->bindParam(':cCatID', $id, PDO::PARAM_INT);
        $updateCat->execute();
        $filasAfectadas = $updateCat->rowCount() > 0;
        return $filasAfectadas;
    }
    
}
?>
