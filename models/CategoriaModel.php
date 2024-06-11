<?php
    class CategoriaModel{
        protected $db;
        protected $categorias;

        public function __construct(){
            $this->db = Conexion::Conexion();
            $this->categorias = array();
        }

        // public function getCategoria(){
        //     $stmt = $this->db->query("SELECT * FROM categoria");
        //     if ( $stmt->num_rows > 0 ){
        //         while ( $fila = $stmt->fetch_assoc()){
        //             $this->categorias[] = $fila;
        //         }
        //     }
        //     $stmt->close();
        //     return $this->categorias;
        // }

        public function getCategoria(){
            $stmt = $this->db->prepare("SELECT * FROM categoria");
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ( $resultado->num_rows > 0){
                while ( $fila = $resultado->fetch_assoc()){
                    $fila["cCatImagen"] = base64_encode($fila["cCatImagen"]);
                    $this->categorias[] = $fila;
                }
            }
            return $this->categorias;
        }


        public function getCategoriaID($id_categoria){
            $categoriaEncontrada = array();
            $stmt = $this->db->prepare("SELECT * FROM categoria WHERE cCatID = ?");
            $stmt->bind_param("i", $id_categoria);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ( $resultado->num_rows > 0){
                while ( $fila = $resultado->fetch_assoc()){
                    $fila["cCatImagen"] = base64_encode($fila["cCatImagen"]);
                    $categoriaEncontrada[] = $fila;
                }
            }
            return $categoriaEncontrada;
        }


        public function idCategoria($data): int {
            $this->db = Conexion::Conexion();
        
            $consulta = $this->db->prepare("SELECT cCatID FROM categoria WHERE cCatNombre = ?");
            $consulta->bind_param("s", $data["categoria"]);
            $exitoso = $consulta->execute();
            if ($exitoso) {
                $consulta->bind_result($idEncontrado);
                $consulta->fetch();
                $consulta->close();
                return $idEncontrado ?? -1;
            } else {
                return -1;
            }
        }
        
        public function getPlatoIDCategoria($id){
            $conn = Conexion::Conexion();
            $platos = array();
            $stmt = $conn->prepare("SELECT p.cPlaID, p.cCatID, p.cPlaNombre, p.cPlaCantidad, p.cPlaPrecio FROM platos p INNER JOIN categoria c ON p.cCatID=c.cCatID 
                                    WHERE c.cCatID = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0){
                while ($fila = $resultado->fetch_array(MYSQLI_NUM)){
                    $platos[] = $fila;
                }
            }
            $stmt->close();
            $resultado->close();
            $conn->close();
            return $platos;
        }
        
        public function getPlatoIDCategoria1($data){
            $conn = Conexion::Conexion();
            $platos = array();
            $stmt = $conn->prepare("SELECT p.* FROM platos p INNER JOIN categoria c ON p.cCatID=c.cCatID 
                                    WHERE c.cCatID = ?");
            $stmt->bind_param("i", $data["cCatID"]);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ( $resultado->num_rows > 0 ){
                while ( $fila = $resultado->fetch_assoc()){
                    $platos[] = $fila;
                }
            }
            $stmt->close();
            $resultado->close();
            $conn->close();
            return $platos;
        }


        public function save($imagen, $nombre): bool{
            $conn = Conexion::Conexion();
            $saveCat = $conn->prepare("INSERT INTO categoria (cCatImagen, cCatNombre) VALUES (?, ?)");
            $saveCat->bind_param("bs", $imagen, $nombre);
            $saveCat->send_long_data(0, $imagen);
            $saveCat->execute();
            $filasAfectadas = $saveCat->affected_rows > 0;
            $saveCat->close();
            $conn->close();
            return $filasAfectadas;
        }

        public function update($id, $imagen, $nombre){
            $conn = Conexion::Conexion();
            $updateCat = $conn->prepare("UPDATE categoria SET cCatImagen = ?, cCatNombre = ? WHERE cCatID = ?");
        }
    }
?>