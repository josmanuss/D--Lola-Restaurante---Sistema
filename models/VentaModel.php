<?php
    class VentaModel{        
        protected $db;
        protected $ventas;
        protected $detalleventa;
        public function __construct(){
            $this->db = Conexion::Conexion();
            $this->ventas = array();
        }
        
        public function maxVenta(): int {
            $numero = 0;
            $consulta = $this->db->prepare("SELECT MAX(iVenID) AS maximo FROM venta");
            $consulta->execute();
            $resultado = $consulta->get_result();
            if ($resultado->num_rows > 0) {
                $fila = $resultado->fetch_assoc();
                $numero = $fila['maximo']; 
            }
            return $numero;
        }
        public function pay($data){
            $stmt = $this->db->prepare("UPDATE pedido SET cPedEstado = 'PAGADO' WHERE cPedID = ?");
            $stmt->bind_param("i", $data);
            $stmt->execute();
            $success = $stmt->affected_rows > 0;
            $stmt->close();
            return $success;   
        }
        
        public function getPago(){
            $pagos = array();
            $stmt = $this->db->prepare("SELECT * FROM pago");
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0){
                while ($row = $resultado->fetch_assoc()){
                    $pagos[] = $row;
                }
            }
            $resultado->close();
            $stmt->close();
            return $pagos;
        }
        public function getComprobante(){
            $fila = null;
            $stmt = $this->db->prepare("SELECT * FROM tipocomprobante");
            $stmt->execute();
            $result = $stmt->get_result();
            if ( $result->num_rows > 0){
                while ( $row = $result->fetch_assoc()){
                    $fila[] = $row;
                }
            }
            $result->close();
            $stmt->close();
            return $fila;
        }
        public function getVentas(){
            $stmt = $this->db->prepare("SELECT * FROM vw_ventas");
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ( $resultado->num_rows > 0 ){
                while ( $fila = $resultado->fetch_assoc()){
                    $this->ventas[] = $fila;
                }
            }
            $stmt->close();
            $resultado->close();
            return $this->ventas;
        }
        public function getVentaID($id){
            $stmt = $this->db->prepare("SELECT * FROM vw_ventas WHERE ID_VENTA = ?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ( $resultado->num_rows > 0 ){
                $venta = $resultado->fetch_assoc();
            }
            $stmt->close();
            $resultado->close();
            return $venta;
        }

        public function getVentaCajero($traID){
            $stmt = $this->db->prepare("SELECT * FROM vw_ventas WHERE ID_TRABAJADOR_CAJERO = ?");
            $stmt->bind_param("i",$traID);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if($resultado->num_rows>0){
                while($fila=$resultado->fetch_assoc()){
                    $venta[] = $fila;
                }
            }
            $resultado->close();
            $stmt->close();
            return $venta;
        }

        public function getDetalleVenta($id){
            $this->detalleventa = array();
            $stmt = $this->db->prepare("CALL ObtenerVentasPorID(?);");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ( $resultado->num_rows > 0 ){
                while ( $fila = $resultado->fetch_array()){
                    $this->detalleventa[] = $fila;
                }
            }
            $resultado->close();
            $stmt->close();
            return $this->detalleventa;
        }

        public function saveSale($venta, $cliente){
            $consulta = $this->db->prepare("INSERT INTO venta(cCliID,cTraID,iTipoComID,fVenTotal) 
            VALUES (?,?,?,?)");
            $consulta->bind_param("iiid",$cliente, $venta->cajero, $venta->comprobante, $venta->monto);
            $consulta->execute();
            $success = $consulta->affected_rows > 0;
            $consulta->close();
            return $success;
        }

        public function saveSaleDetail($id_venta, $data){
            $consulta = $this->db->prepare("INSERT INTO detalleventa(iVenID, iPlaID, iDetCantidad) VALUES(?,?,?);");
            $consulta->bind_param("iii",$id_venta,$data->idPlato,$data->cantidad);
            $consulta->execute();
            $consulta->close();
        }

        public function saveDetailPay($id_venta,$data){
            $consulta = $this->db->prepare("INSERT INTO detallepagos(iVenID,cPagoID,fDetPagCantidad) VALUES(?,?,?);");
            $consulta->bind_param("iid",$id_venta, $data->tipoPago, $data->totalPagado);
            $consulta->execute();
            $consulta->close();
        }

        public function reportProducts(){
            $productos = array(); 
            $consulta = $this->db->prepare("SELECT * FROM `vw_productos_vendidos` ORDER BY `vw_productos_vendidos`.`TotalCantidad` DESC;");
            $consulta->execute();
            $resultado = $consulta->get_result();
            if ($resultado->num_rows > 0){
                while ($row = $resultado->fetch_array(MYSQLI_NUM)){ 
                    $productos[] = $row; 
                }
            }
            $resultado->close();
            $consulta->close();
            return $productos;
        }
        
    }
?>