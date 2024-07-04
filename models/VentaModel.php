<?php

class VentaModel {
    protected $db;
    protected $ventas;
    protected $detalleventa;

    public function __construct() {
        $this->db = Conexion::ConexionSQL();
        $this->ventas = array();
    }

    public function maxVenta(): int {
        $numero = 0;
        $consulta = $this->db->prepare("SELECT MAX(iVenID) AS maximo FROM venta");
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        if ($resultado) {
            $numero = $resultado['maximo'];
        }
        return $numero;
    }

    public function pay($data) {
        $stmt = $this->db->prepare("UPDATE pedido SET cPedEstado = 'PAGADO' WHERE cPedID = :cPedID");
        $stmt->bindParam(":cPedID", $data, PDO::PARAM_INT);
        $stmt->execute();
        $success = $stmt->rowCount() > 0;
        return $success;
    }

    public function getPago() {
        $pagos = array();
        $stmt = $this->db->prepare("SELECT * FROM pago");
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultado) {
            $pagos = $resultado;
        }
        return $pagos;
    }

    public function getComprobante() {
        $fila = null;
        $stmt = $this->db->prepare("SELECT * FROM tipocomprobante");
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultado) {
            $fila = $resultado;
        }
        return $fila;
    }

    public function getVentas() {
        $stmt = $this->db->prepare("SELECT * FROM vw_ventas");
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultado) {
            $this->ventas = $resultado;
        }
        return $this->ventas;
    }

    public function getVentaID($id) {
        $stmt = $this->db->prepare("SELECT * FROM vw_ventas WHERE ID_VENTA = :ID_VENTA");
        $stmt->bindParam(":ID_VENTA", $id, PDO::PARAM_INT);
        $stmt->execute();
        $venta = $stmt->fetch(PDO::FETCH_ASSOC);
        return $venta;
    }

    public function getVentaCajero($traID) {
        $venta = [];
        $stmt = $this->db->prepare("SELECT * FROM vw_ventas WHERE ID_TRABAJADOR_CAJERO = :ID_TRABAJADOR_CAJERO");
        $stmt->bindParam(":ID_TRABAJADOR_CAJERO", $traID, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultado) {
            $venta = $resultado;
        }
        return $venta;
    }

    public function getDetalleVenta($id) {
        $this->detalleventa = array();
        $stmt = $this->db->prepare("CALL ObtenerVentasPorID(:ID_VENTA)");
        $stmt->bindParam(":ID_VENTA", $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultado) {
            $this->detalleventa = $resultado;
        }
        return $this->detalleventa;
    }

    public function saveSale($venta, $mesa, $cliente) {
        $consulta = $this->db->prepare("INSERT INTO venta(cMesID, cCliID, cTraID, iTipoComID, fVenTotal) VALUES (:cMesID, :cCliID, :cTraID, :iTipoComID, :fVenTotal)");
        $consulta->bindParam(":cMesID", $mesa, PDO::PARAM_INT);
        $consulta->bindParam(":cCliID", $cliente, PDO::PARAM_INT);
        $consulta->bindParam(":cTraID", $venta->cajero, PDO::PARAM_INT);
        $consulta->bindParam(":iTipoComID", $venta->comprobante, PDO::PARAM_INT);
        $consulta->bindParam(":fVenTotal", $venta->monto, PDO::PARAM_STR);
        $consulta->execute();
        $success = $consulta->rowCount() > 0;
        return $success;
    }

    public function saveSaleDetail($id_venta, $data) {
        $consulta = $this->db->prepare("INSERT INTO detalleventa(iVenID, iPlaID, iDetCantidad) VALUES (:iVenID, :iPlaID, :iDetCantidad)");
        $consulta->bindParam(":iVenID", $id_venta, PDO::PARAM_INT);
        $consulta->bindParam(":iPlaID", $data->idPlato, PDO::PARAM_INT);
        $consulta->bindParam(":iDetCantidad", $data->cantidad, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function saveDetailPay($id_venta, $data) {
        $consulta = $this->db->prepare("INSERT INTO detallepagos(iVenID, cPagoID, fDetPagCantidad) VALUES (:iVenID, :cPagoID, :fDetPagCantidad)");
        $consulta->bindParam(":iVenID", $id_venta, PDO::PARAM_INT);
        $consulta->bindParam(":cPagoID", $data->tipoPago, PDO::PARAM_STR);
        $consulta->bindParam(":fDetPagCantidad", $data->totalPagado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public function reportProducts() {
        $productos = array();
        $consulta = $this->db->prepare("SELECT * FROM vw_productos_vendidos ORDER BY TotalCantidad DESC");
        $consulta->execute();
        $resultado = $consulta->fetchAll(PDO::FETCH_NUM);
        if ($resultado) {
            $productos = $resultado;
        }
        return $productos;
    }
}
?>
