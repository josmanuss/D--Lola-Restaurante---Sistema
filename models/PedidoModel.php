<?php
class PedidoModel{
    public $db;
    protected $pedido;
    protected $detallepedido;

    public function __construct(){
        $this->db = Conexion::Conexion();
    }   


    public function idCliente($id){
        $stmt = $this->db->prepare("SELECT cCliID FROM pedido WHERE cPedID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->num_rows > 0 ? $resultado->fetch_assoc()["cCliID"] : null;
    }
    

    public function idMesa($id){
        $stmt = $this->db->prepare("SELECT cMesID FROM pedido WHERE cPedID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->num_rows > 0 ? $resultado->fetch_assoc()["cMesID"] : null;
    }
    
    public function selectTableOrder() {
        $stmt = $this->db->prepare("SELECT * FROM mesa WHERE estado = 'LIBRE'");
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        return $resultado->fetch_all(MYSQLI_ASSOC);
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

    public function pay($data){
        $data = intval($data);
        $stmt = $this->db->prepare("UPDATE pedido SET cPedEstado = 'PAGADO' WHERE cPedID = ?");
        $stmt->bind_param("i", $data);
        $stmt->execute();
        $success = $stmt->affected_rows > 0;
        $stmt->close();
        return $success;   
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
        $this->detallepedido = array();
        $stmt = $this->db->prepare("CALL ObtenerPedidosPorID(?);");
        $stmt->bind_param("i",$id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ( $resultado->num_rows > 0 ){
            while ( $fila = $resultado->fetch_array()){
                $this->detallepedido[] = $fila;
            }
        }
        $resultado->close();
        $stmt->close();
        return $this->detallepedido;
    }



    
    public function saveOrder($id_mesa,$id_usuario, $id_trabajador, $precioTotal){
        $consulta = $this->db->prepare("INSERT INTO pedido(cMesID, cCliID, cTraID, cPedTotal) VALUES (?,?,?,?)");
        $consulta->bind_param("iiid",$id_mesa,$id_usuario, $id_trabajador, $precioTotal);
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


}