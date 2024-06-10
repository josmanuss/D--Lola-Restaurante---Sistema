<?php
    class VentaModel{        
        protected $db;
        protected $pedido;
        protected $detallepedido;
        protected $ventas;
        protected $detalleventa;
        public function __construct(){
            $this->db = Conexion::Conexion();
            $this->pedido = array();
            $this->ventas = array();
        }
        public function pay($data){
            $stmt = $this->db->prepare("UPDATE venta SET fPedTotal = ?, cVenEstado = 'Pagada' WHERE iVenID = ?");
            $stmt->bind_param("di", $data["monto_pagado"], $data["id_venta"]);
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
            $stmt = $this->db->prepare("SELECT * FROM venta");
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
            $stmt = $this->db->prepare("SELECT * FROM venta WHERE iVenID = ?");
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


        public function getPedido(){
            $stmt = $this->db->prepare("SELECT * FROM vw_pedidos WHERE Estado = 'EN_PROCESO_VENTA'");
            $stmt->execute();
            $resultado = $stmt->get_result();
            if($resultado->num_rows>0){
                while($fila=$resultado->fetch_assoc()){
                    $this->pedido[] = $fila;
                }
            }
            $stmt->close();
            return $this->pedido;
        }

        public function getPedidoID($id): array{
            $pedido = array();
            $stmt = $this->db->prepare("SELECT * FROM vw_pedidos WHERE ID_Pedido = ?");
            $stmt->bind_param("i",$id);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if($resultado->num_rows>0){
                while($fila=$resultado->fetch_assoc()){
                    $pedido[] = $fila;
                }
            }
            $stmt->close();
            return $pedido;
        }


        public function maxPedido(): int {
            $numero = 0;
            $consulta = $this->db->prepare("SELECT MAX(cPedID) AS maximo FROM pedido");
            $consulta->execute();
            $resultado = $consulta->get_result();
            if ($resultado->num_rows > 0) {
                $fila = $resultado->fetch_assoc();
                $numero = $fila['maximo']; 
            }
            return $numero;
        }

        public function getDetallePedido($id) : array{
            $this->detalleventa = array();
            $stmt = $this->db->prepare("CALL ObtenerPedidosPorID(?);");
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

        public function getDetalleVenta($id){
            $this->detalleventa = array();
            $stmt = $this->db->prepare("CALL mostrarDetalleVentaID(?);");
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

        public function saveOrder($id_usuario, $id_trabajador, $precioTotal){
            $consulta = $this->db->prepare("INSERT INTO pedido(cCliID, cTraID, cPedTotal) VALUES (?,?,?)");
            $consulta->bind_param("iid",$id_usuario, $id_trabajador, $precioTotal);
            $consulta->execute();
            $success = $consulta->affected_rows > 0;
            $consulta->close();
            return $success;
        }

        public function saveOrderDetail($id_venta, $data){
            $consulta = $this->db->prepare("INSERT INTO detallepedido(cPedID, cPlaID, iDepCantidad) VALUES(?,?,?);");
            $consulta->bind_param("iii",$id_venta,$data["id_plato"],$data["cantidad"]);
            $consulta->execute();
            $consulta->close();
        }
        public function saveSale($venta){
            $consulta = $this->db->prepare("INSERT INTO venta(cUsuID, fPedTotal) VALUES (?)");
            $consulta->bind_param("i",$venta);
            $consulta->execute();
            $success = $consulta->affected_rows > 0;
            $consulta->close();
            return $success;
        }

        public function saveSaleDetail($id_venta, $data){
            $consulta = $this->db->prepare("INSERT INTO detalleventa(iVenID, iPlaID, iDetCantidad) VALUES(?,?,?);");
            $consulta->bind_param("iii",$id_venta,$data["id_plato"],$data["cantidad"]);
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