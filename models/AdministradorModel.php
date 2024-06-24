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
        $this->db = Conexion::Conexion();
    }

    public function cantidadClientes(): int{
        $sql = $this->db->query("SELECT COUNT(*) FROM recuperarclientes");
        if ( $sql->num_rows > 0 ){
            $fila = $sql->fetch_assoc();
            $this->clientes = $fila["COUNT(*)"];
        }
        $sql->close();
        return $this->clientes;
    }
    
    public function cantidadTrabajadores(): int {
        $sql = $this->db->query("SELECT COUNT(*) AS cantidad FROM usuario WHERE cUserRol <> 'normal'");
        if ($sql) { 
            $fila = $sql->fetch_assoc(); 
            $this->trabajadores = $fila['cantidad']; 
        }
        $sql->close();
        return $this->trabajadores;
    }
    public function cantidadTrabajadoresActivos(): int {
        $sql = $this->db->prepare("SELECT COUNT(*) AS cantidad FROM usuario WHERE cUserRol <> 'normal' AND cUsuActivo = 1");
        $sql->execute(); 
        $this->trabajadores = 0;
        $result = $sql->get_result();
        if ($result->num_rows > 0) { 
            $fila = $result->fetch_assoc(); 
            $this->trabajadores = $fila['cantidad']; 
        }
        
        $sql->close(); 
        return $this->trabajadores;
    }
    
    public function cantidadCategorias(): int{
        $sql = $this->db->query("SELECT COUNT(*) FROM categoria");
        if ( $sql->num_rows > 0){
            $fila = $sql->fetch_assoc();
            $this->categorias = $fila["COUNT(*)"];
        }
        $sql->close();
        return $this->categorias;
    }


    public function cantidadPedidosPendientes(): int{
        $sql = $this->db->query("SELECT COUNT(*) FROM pedido WHERE cPedEstado != 'PAGADO'");
        if($sql->num_rows>0){
            $fila = $sql->fetch_assoc();
            $this->pedidos = $fila["COUNT(*)"];
        }
        $sql->close();
        return $this->pedidos;
    }
    public function cantidadPlatos(): int{
        $sql = $this->db->query("SELECT COUNT(*) FROM platos");
        if ( $sql->num_rows > 0){
            $fila = $sql->fetch_assoc();
            $this->platos = $fila["COUNT(*)"];
        }
        $sql->close();
        return $this->platos;
    }

    public function totalVentasHechas(){
        $sql = $this->db->prepare("SELECT COUNT(*) AS TotalVentas FROM venta");
        $sql->execute();
        $result = $sql->get_result();
        if ( $result->num_rows == 0 ){
            $sql->close();
            return $this->ventas;
        }
        else{
            while ( $row = $result->fetch_assoc()){
                $this->ventas = $row["TotalVentas"];
            }
            $sql->close();
            return $this->ventas;
        }
    }

    public function gananciasVentas(){
        
        $sql = $this->db->prepare("SELECT SUM(fVenTotal) AS TotalGanancias FROM venta");
        $sql->execute();
        $result = $sql->get_result();
        if ( $result->num_rows == 0 ){
            $sql->close();
            return $this->ganancias;
        }
        else{
            while ( $row = $result->fetch_assoc()){
                $this->ganancias = $row["TotalGanancias"];
            }
            $sql->close();
            return $this->ganancias;
        }
    }


}