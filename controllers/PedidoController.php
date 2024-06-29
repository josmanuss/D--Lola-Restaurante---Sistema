<?php

require("pdf/code128.php");
class PedidoController{

    protected $pedido;
    protected $platos;
    protected $clientes;
    protected $ventaGuardar;
    protected $pdf;
    public function __construct(){
        require_once "models/PedidoModel.php";
        require_once "models/PlatoModel.php";
        require_once "models/ClienteModel.php";
        require_once "models/VentaModel.php";
        $this->pedido = new PedidoModel();
        $this->platos = new PlatoModel();
        $this->clientes = new ClienteModel();
        $this->ventaGuardar = new VentaModel();
        $this->pdf = new PDF_Code128('P','mm',array(80,258));
    }
    
    public function index(): void {
        if ( session_status() == PHP_SESSION_NONE){
            session_start();
        }
        $carID = isset($_SESSION["trabajador"]["iCarID"]) ? intval($_SESSION["trabajador"]["iCarID"]) : 0;
        if ($carID == 2) {
            $this->verPedidosCajero();
        } elseif ($carID == 3) {
            $this->verMesasDisponibles(); 
        } else {
            $this->showError404();
        }
        
    }

    public function verMesasDisponibles(){
        $data["titulo"] = "SELECCIONA UNA MESA DISPONIBLE";
        $data["mesa"] = $this->pedido->selectTableOrder();

        //echo "<pre>"; print_r($data["mesa"]); "</pre>"; exit();
        $data["contenido"] = "views/venta/seleccionar_mesa.php";
        require_once TEMPLATE;
    }
    
    public function verDetallePedido(){
        if ( $_SERVER["REQUEST_METHOD"] === "POST"){
            $recordID = $_POST["record_id"];
            $data = $this->pedido->getDetallePedido(intval($recordID));
            if ( $data != null ){
                echo json_encode(["success"=>true, "detalle"=>$data]);
            }
            else{
                echo json_encode(["success"=>false]);
            }
        }
    }
    
    public function realizarPedido($id) : void {
        $data["titulo"] = "Realizar pedido";
        $data["dni"] = $this->clientes->clientesDNI();
        $_SESSION["mesa"] = [$id];
        //echo "<pre>"; print_r($id); "</pre>"; exit();
        $data["contenido"] = "views/venta/realizar_pedido.php";
        require_once TEMPLATE;
    }

    public function actualizarPedido(): void{
        if ($_SERVER["REQUEST_METHOD"]==='POST'){
            $id_modificar = $_POST["id_pedido"];
            $data["modalTitulo"] = "Actualizar Pedido";
            $data["pedidoModificar"] = json_decode($_POST["pedido"]);
            require_once "views/venta/modal_actualizar_pedido";
        }
        else{
            require_once ERROR404;
        }
    }

    public function pagarPedido() : void {
        if ($_SERVER["REQUEST_METHOD"] === "POST" ){
            $record_id = $_POST["record_id"];
            $data["titulo"] = "Pagar venta";
            $data["comprobante"] = $this->pedido->getComprobante();
            $data["tipoPago"] = $this->pedido->getPago();
            $data["contenido"] = "views/venta/pagar_pedido.php";
            require_once TEMPLATE;
        }
    }

    public function metodoPagarPedido(): void {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try{
                $datosVenta = json_decode($_POST["datos_venta"]);
                $datosDetalleVenta = json_decode($_POST["datos_detalleventa"]);
                $datosDetallePagos = json_decode($_POST["datos_detallepagos"]);
                $cliente = $this->pedido->idCliente($datosVenta[0]->idPedido);
                $mesa = $this->pedido->idMesa($datosVenta[0]->idPedido);
                $exitoso1 = $this->ventaGuardar->saveSale($datosVenta[0], $mesa, $cliente);
                if ($exitoso1) {
                    $idVenta = $this->ventaGuardar->maxVenta();
                    if ($idVenta > 0) {
                        foreach ($datosDetalleVenta as $detalleVenta) {
                            $this->ventaGuardar->saveSaleDetail($idVenta, $detalleVenta);
                        }
                        foreach ($datosDetallePagos as $detallePagos) {
                            $this->ventaGuardar->saveDetailPay($idVenta, $detallePagos);
                        }
                        $this->pedido->pay($datosVenta[0]->idPedido);
                        echo json_encode(["success"=>true, "mensaje"=>"Venta pagada correctamente"]);
                    }
                }
            }
            catch(Exception $e){
                echo json_encode(["success"=>false, "mensaje"=> $e->getMessage()]);
            }
        }
    }
    
    public function verPedidosCajero() : void {
        $data["titulo"]= "Pedidos a pagar";
        $data["pedido"] = $this->pedido->getPedido();
        $data["contenido"] = "views/venta/pedido_cajero.php";
        require_once TEMPLATE;
    }

    
    public function agregarPedido() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            try{
                $pedido = json_decode($_POST["valores_pedido"]);

                $detalle_pedido = json_decode($_POST["valores_detalle_pedido"]);
                $columnas_detalle = ["id_plato", "id_categoria", "nombre", "cantidad", "precio"];
                $detalle_pedido_agregar = array_map(function($fila) use ($columnas_detalle) {
                    return array_combine($columnas_detalle, $fila);
                }, $detalle_pedido);

                $exitoso1 = $this->pedido->saveOrder($pedido[0],$pedido[1],$pedido[2],$pedido[3]);
                if ($exitoso1 == TRUE) {
                    $idVenta = $this->pedido->maxPedido();
                    if ($idVenta > 0) {
                        foreach ($detalle_pedido_agregar as $detalle) {
                            $this->pedido->saveOrderDetail($idVenta, $detalle);
                        }
                        echo json_encode(["success" => true, "mensaje" => "El pedido se ha registrado correctamente"]);
                    } else {
                        echo json_encode(["success" => false, "mensaje" => "Hubo un error con el id de pedido"]);
                    }
                } else {
                    echo json_encode(["success" => false, "mensaje" => "No se pudo realizar la operacion"]);
                }
            }
            catch ( Exception $e ){
                echo json_encode(["success"=>false,"mensaje"=>$e->getMessage()]);
            }
        }
    }


    public function generarTicketOrden($id){
        $datos_pedido = $this->pedido->getPedidoID($id);
        $datosDetallePedido = $this->pedido->getDetallePedido($id);
        $nombreEmpresa = "D' LOLA RESTAURANTE CIX ©";
        $numeroPedido = $id;
        if ( empty($datos_pedido[0]["NombreApellidoCliente"])){
            $cliente = $datos_pedido[0]["TipoCliente"];
        }
        else{
            $cliente = $datos_pedido[0]["NombreApellidoCliente"];
        }
        $igv = 0.18;
        $total = 0;
        $this->pdf->SetMargins(4,10,4);
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial','B',10);
        $this->pdf->SetTextColor(0,0,0);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1", strtoupper($nombreEmpresa)),0,'C',false);
        $this->pdf->SetFont('Arial','',9);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","RUC: 1729278258"),0,'C',false);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Direccion: C. Teodoro Cárdenas 133, Lima 15046"),0,'C',false);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Teléfono: 955222600"),0,'C',false);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Email: DLOLARESTAURANTE@DLOLA.COM"),0,'C',false);
        $this->pdf->SetFont('Arial','',9);
        $this->pdf->Ln(1);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1", "ORDEN N°: " . $numeroPedido),0,'C',false);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1", "Cliente: " . $cliente),0,'C',false);
        $this->pdf->Ln(1);

        $this->pdf->Ln(1);
        $this->pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
        $this->pdf->Ln(5);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Fecha: ".$datos_pedido[0]["Fecha"]),0,'C',false);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Pedido Nro: ".$id),0,'C',false);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Cajero: ".$datos_pedido[0]["NombreApellidoMozo"]),0,'C',false);
        $this->pdf->Ln(1);
        $this->pdf->Ln(1);
        $this->pdf->SetFont('Arial','B',10);
        $this->pdf->SetFont('Arial','',9);
        # Tabla de productos #
        $this->pdf->Ln(1);
        $this->pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
        $this->pdf->Ln(3);
        $this->pdf->Cell(10,5,iconv("UTF-8", "ISO-8859-1","Cant."),0,0,'C');
        $this->pdf->Cell(19,5,iconv("UTF-8", "ISO-8859-1","Precio"),0,0,'C');
        $this->pdf->Cell(15,5,iconv("UTF-8", "ISO-8859-1","Nombre"),0,0,'C');
        $this->pdf->Cell(28,5,iconv("UTF-8", "ISO-8859-1","Precio Final"),0,0,'C');
        $this->pdf->Ln(3);
        $this->pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
        $this->pdf->Ln(5);
        /*----------  Detalles de la tabla  ----------*/
        foreach ($datosDetallePedido as $detallePedido){
            $this->pdf->Cell(10,4,iconv("UTF-8", "ISO-8859-1",$detallePedido["Cantidad"]),0,0,'C');
            $this->pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1",$detallePedido["Precio"]),0,0,'C');
            $this->pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1",$detallePedido["NombrePlato"]),0,0,'C');
            $this->pdf->Cell(28,4,iconv("UTF-8", "ISO-8859-1",$detallePedido["PrecioFinal"]),0,0,'C');
            $this->pdf->Ln(4);
            $total += $detallePedido["PrecioFinal"];
        }            
        $this->pdf->Ln(7);
        /*----------  Fin Detalles de la tabla  ----------*/
        $this->pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
        $this->pdf->Ln(5);
        # Impuestos & totales #
        $this->pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $this->pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","OP.GRAVADAS: "),0,0,'C');
        $this->pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",strval(floatval($total - ($total*$igv)))),0,0,'C');
        $this->pdf->Ln(5);

        $this->pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $this->pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","IGV (18%)"),0,0,'C');
        $this->pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",strval(floatval($total*$igv))),0,0,'C');
        $this->pdf->Ln(5);

        $this->pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $this->pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","SUBTOTAL: "),0,0,'C');
        $this->pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",strval(floatval($total))),0,0,'C');
        $this->pdf->Ln(5);
        
        $this->pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $this->pdf->Cell(70,20,iconv("UTF-8", "ISO-8859-1","Total a cancelar: S/.".strval(floatval($total))),0,0,'C');

        $this->pdf->SetXY(0,$this->pdf->GetY()+21);
        $this->pdf->SetFont('Arial','',14);

 
        # Nombre del archivo PDF #
        $this->pdf->Output("I","Orden_Nro_".$id.".pdf",true);
    }
    
    private function showError404() : void {
        if (defined('ERROR404')) {
            require_once ERROR404;
        } else {
            echo "Error 404: Página no encontrada";
        }
    }

}