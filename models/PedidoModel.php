<?php
class PedidoModel{
    public $db;
    protected $pedido;
    protected $detallepedido;

    public function __construct(){
        $this->db = Conexion::ConexionSQL();
    }   


    public function idCliente($id) {
        $stmt = $this->db->prepare("SELECT cCliID FROM pedido WHERE cPedID = :cPedID");
        $stmt->bindParam(":cPedID", $id, PDO::PARAM_STR);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado["cCliID"] : null;
    }
    

    public function idMesa($id) {
        $stmt = $this->db->prepare("SELECT cMesID FROM pedido WHERE cPedID = :cPedID");
        $stmt->bindParam(":cPedID", $id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado ? $resultado["cMesID"] : null;
    }
    
    
    public function selectTableOrder() {
        $stmt = $this->db->prepare("SELECT * FROM mesa");
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;
    }
    
    public function getPago() {
        $stmt = $this->db->prepare("SELECT * FROM pago");
        $stmt->execute();
        $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return isset($pagos) ? $pagos: null;
    }
    
    public function getComprobante() {
        $stmt = $this->db->prepare("SELECT * FROM tipocomprobante");
        $stmt->execute();
        $fila = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $fila;
    }
    

    public function pay($data) {
        $data = intval($data);
        $stmt = $this->db->prepare("UPDATE pedido SET cPedEstado = 'PAGADO' WHERE cPedID = :cPedID");
        $stmt->bindParam(":cPedID", $data, PDO::PARAM_INT);
        $stmt->execute();
        $success = $stmt->rowCount() > 0; // Verifica si se afectó alguna fila (al menos una fila actualizada)
        return $success;
    }
    

    public function sendOrder($id) {
        $stmt = $this->db->prepare("UPDATE pedido SET cPedEstado = 'EN_PROCESO_VENTA' WHERE cPedID = :cPedID");
        $stmt->bindParam(":cPedID", $id, PDO::PARAM_INT);
        $stmt->execute();
        $success = $stmt->rowCount() > 0;
        return $success;
    }
    
    public function getPedido(){
        $stmt = $this->db->prepare("SELECT * FROM vw_pedidos");
        $stmt->execute();
        $this->pedido = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->pedido;
    }

    public function getPedidoID($id): array{
        $pedido = array();
        $stmt = $this->db->prepare("SELECT * FROM vw_pedidos WHERE ID_Pedido = :ID_Pedido");
        $stmt->bindParam(":ID_Pedido",$id, PDO::PARAM_INT);
        $stmt->execute();
        $pedido = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $pedido;
    }
    public function getPedidoMozo($id): array {
        $stmt = $this->db->prepare("SELECT * FROM vw_pedidos WHERE ID_Trabajador_Mozo = :ID_TrabajadorMozo");
        $stmt->bindParam(':ID_Trabajador_Mozo', $id, PDO::PARAM_INT);
        $stmt->execute();
        $pedido = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $pedido;
    }
    

    public function maxPedido(): int {
        $numero = 0;
        $sql = "SELECT MAX(cPedID) AS maximo FROM pedido";
        $consulta = $this->db->prepare($sql);
        $consulta->execute();
        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
        if ($resultado && isset($resultado['maximo'])) {
            $numero = (int) $resultado['maximo'];
        }
        
        return $numero;
    }
    

    public function getDetallePedido($id): array {
        $sql = "CALL ObtenerPedidosPorID(:pedidoID)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":pedidoID", $id, PDO::PARAM_INT);
        $stmt->execute();
        $this->detallepedido = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();        
        return $this->detallepedido;
    }
    
    
    public function saveOrder($id_mesa,$id_usuario, $id_trabajador, $precioTotal){
        $consulta = $this->db->prepare("INSERT INTO pedido(cMesID, cCliID, cTraID, cPedTotal) VALUES (:cMesID,:cCliID,:cTraID,:cPedTotal)");
        $consulta->bindParam(":cMesID", $id_mesa, PDO::PARAM_INT);
        $consulta->bindParam(":cCliID", $id_usuario, PDO::PARAM_INT);
        $consulta->bindParam(":cTraID", $id_trabajador, PDO::PARAM_INT);
        $consulta->bindParam(":cPedTotal", $precioTotal, PDO::PARAM_INT);
        $consulta->execute();
        $success = $consulta->rowCount() > 0;
        $consulta->closeCursor();
        return $success;
    }

    public function saveOrderDetail($id_venta, $data){
        $sql = "INSERT INTO detallepedido(cPedID, cPlaID, iDepCantidad) VALUES(:cPedID, :cPlaID, :iDepCantidad)";
        $consulta = $this->db->prepare($sql);
        $consulta->bindParam(':cPedID', $id_venta, PDO::PARAM_INT);
        $consulta->bindParam(':cPlaID', $data["id_plato"], PDO::PARAM_INT);
        $consulta->bindParam(':iDepCantidad', $data["cantidad"], PDO::PARAM_INT);
        $consulta->execute();
        $consulta->closeCursor();
    }
    
}