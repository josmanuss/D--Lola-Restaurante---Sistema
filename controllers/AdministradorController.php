<?php
class AdministradorController{
    protected $admin;
    public function __construct(){
        if ( session_status() == PHP_SESSION_NONE){
            session_start();
        }
        require_once "models/AdministradorModel.php";
        $this->admin = new AdministradorModel();
    }
    public function index(): void{
        if (isset($_SESSION["trabajador"]) && $_SESSION["trabajador"]["iCarID"] == 1) {
            $data["cantidades"] = array(
                'cliente' => $this->admin->cantidadClientes(),
                'trabajador' => $this->admin->cantidadTrabajadores(),
                'trabajador_activo' => $this->admin->cantidadTrabajadoresActivos(),
                'categoria' => $this->admin->cantidadCategorias(),
                'pedido' => $this->admin->cantidadPedidosPendientes(),
                'plato' => $this->admin->cantidadPlatos(),
                'venta' => $this->admin->totalVentasHechas(),
                'ganancia' => $this->admin->gananciasVentas()

            );
            $data["contenido"] = "views/administrador/administrador.php";
            require_once TEMPLATE;
        } else {
            require_once ERROR404;
        }
    }


    public function reportesVentasMensuales(){
        if (isset($_SESSION["trabajador"]) && $_SESSION["trabajador"]["iCarID"] == 1) {
            $data = $this->admin->reportSalesMonth();
            echo json_encode(["success" => true, "ventas" => $data]);
        } else {
            require_once ERROR404;
        }
    }
    
    
}