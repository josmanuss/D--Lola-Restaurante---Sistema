<?php
class TipoDocumentoModel {
    protected $db;
    protected $tipoDocumento;

    public function __construct() {
        $this->db = Conexion::ConexionSQL();
        $this->tipoDocumento = array();
    }

    public function getTipoDocumento() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM TipoDocumento");
            $stmt->execute();
            $this->tipoDocumento = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
        return $this->tipoDocumento;
    }

    public function save($nombre) {
        try {
            $stmt = $this->db->prepare("INSERT INTO tipodocumento(tTipoDocNombre) VALUES (:tTipoDocNombre)");
            $stmt->bindParam(':tTipoDocNombre', $nombre, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function update($id, $nombre) {
        try {
            $stmt = $this->db->prepare("UPDATE tipodocumento SET tTipoDocNombre = :nombre WHERE iTipoDocID = :iTipoDocID");
            $stmt->bindParam(':tTipoDocNombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':iTipoDocID', $id, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
