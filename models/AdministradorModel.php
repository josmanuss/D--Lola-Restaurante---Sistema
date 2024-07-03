<?php
class AdministradorModel{
    protected $clientes;
    protected $trabajadores;
    protected $trabajadoresActivos;
    protected $categorias;
    protected $platos;
    protected $pedidos;
    protected $ventas;
    protected $ganancias;

    protected $db;
    public function __construct(){
        $this->clientes = 0;
        $this->trabajadores = 0;
        $this->trabajadoresActivos = 0;
        $this->categorias = 0;
        $this->platos = 0;
        $this->pedidos = 0;
        $this->ventas = 0;
        $this->ganancias = 0;
        $this->db = Conexion::ConexionSQL();
    }

    public function cantidadClientes(): int {
        $sql = $this->db->query("SELECT COUNT(*) AS count FROM recuperarclientes");
        $fila = $sql->fetch(PDO::FETCH_ASSOC);
        $this->clientes = (int) $fila['count'];
        return $this->clientes;
    }

    public function cantidadTrabajadores(): int {
        $sql = $this->db->query("SELECT COUNT(*) AS cantidad FROM usuario WHERE cUserRol <> 'normal'");
        $fila = $sql->fetch(PDO::FETCH_ASSOC);
        $this->trabajadores = (int) $fila['cantidad'];
        return $this->trabajadores;
    }

    public function cantidadTrabajadoresActivos(): int {
        $sql = $this->db->query("SELECT COUNT(*) AS cantidad FROM usuario WHERE cUserRol <> 'normal' AND cUsuActivo = 1");
        $fila = $sql->fetch(PDO::FETCH_ASSOC);
        $this->trabajadores = (int) $fila['cantidad'];
        return $this->trabajadores;
    }

    public function cantidadCategorias(): int {
        $sql = $this->db->query("SELECT COUNT(*) AS cantidad FROM categoria");
        $fila = $sql->fetch(PDO::FETCH_ASSOC);
        $this->categorias = (int) $fila['cantidad'];
        return $this->categorias;
    }

    public function cantidadPedidosPendientes(): int {
        $sql = $this->db->query("SELECT COUNT(*) AS cantidad FROM pedido WHERE cPedEstado != 'PAGADO'");
        $fila = $sql->fetch(PDO::FETCH_ASSOC);
        $this->pedidos = (int) $fila['cantidad'];
        return $this->pedidos;
    }

    public function cantidadPlatos(): int {
        $sql = $this->db->query("SELECT COUNT(*) AS cantidad FROM platos");
        $fila = $sql->fetch(PDO::FETCH_ASSOC);
        $this->platos = (int) $fila['cantidad'];
        return $this->platos;
    }

    public function totalVentasHechas(): int {
        $sql = $this->db->query("SELECT COUNT(*) AS TotalVentas FROM venta");
        $fila = $sql->fetch(PDO::FETCH_ASSOC);
        $this->ventas = (int) $fila['TotalVentas'];
        return $this->ventas;
    }

    public function gananciasVentas(): float {
        $sql = $this->db->query("SELECT SUM(fVenTotal) AS TotalGanancias FROM venta");
        $fila = $sql->fetch(PDO::FETCH_ASSOC);
        $this->ganancias = (float) $fila['TotalGanancias'];
        return $this->ganancias;
    }

    
    public function reportSalesMonth(){
        $totalVentas = array();
        $stmt = $this->db->prepare("SELECT MONTH(v.dVenFecha) AS Mes,
        SUM(dv.iDetCantidad) AS CantidadVendida FROM venta v INNER JOIN 
        detalleventa dv ON v.iVenID = dv.iVenID GROUP BY Mes ORDER BY Mes;");
        $stmt->execute();
        $totalVentas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;
        return $totalVentas;
    }


}