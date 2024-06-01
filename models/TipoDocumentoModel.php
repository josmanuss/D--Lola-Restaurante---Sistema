<?php

class TipoDocumentoModel{
    protected $db;
    protected $tipoDocumento;

    public function __construct(){
        $this->db = Conexion::Conexion();
        $this->tipoDocumento = array();
    }

    public function getTipoDocumento(){
        $conn  = Conexion::Conexion();
        $prepTD = $conn->prepare("SELECT * FROM TipoDocumento");
        $prepTD->execute();
        $resultado = $prepTD->get_result();
        if ( $resultado->num_rows > 0 ){
            while ( $fila = $resultado->fetch_assoc()){
                $this->tipoDocumento[] = $fila;        
            }
        }
        $prepTD->close();
        $conn->close();
        return $this->tipoDocumento;
    }
    
    public function save( $nombre ){
        $conn  = Conexion::Conexion();
        $stmt = $conn->prepare("INSERT INTO tipodocumento(tTipoDocNombre) VALUES (?);");
        $stmt->bind_param("s",$nombre);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $success;
    }


    public function update( $id,$nombre ){
        $conn  = Conexion::Conexion();
        $stmt = $conn->prepare("UPDATE tipodocumento SET tTipoDocNombre = ? WHERE iTipoDocID = ?;");
        $stmt->bind_param("si",$nombre,$id);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();
        return $success;
    }


}