<?php

require("pdf/code128.php");
class VentaController {
    protected $venta;
    protected $pedido;
    protected $platos;
    protected $clientes;

    private $pdf;
    public function __construct() {
        if ( session_status() == PHP_SESSION_NONE){
            session_start();
        }
        require_once "models/VentaModel.php";
        require_once "models/PlatoModel.php";
        require_once "models/ClienteModel.php";
        $this->venta = new VentaModel();
        $this->platos = new PlatoModel();
        $this->clientes = new ClienteModel();
        $this->pdf = new PDF_Code128('P','mm',array(80,258));
    }

    public function index(): void {
        $carID = isset($_SESSION["trabajador"]["iCarID"]) ? intval($_SESSION["trabajador"]["iCarID"]) : 0;
        if ($carID === 1) {
            $this->verVentasAdministrador();
        } 
        else if ( $carID === 2 ){
            $traID = isset($_SESSION["trabajador"]["cTraID"]) ? intval($_SESSION["trabajador"]["cTraID"]) : 0;
            $this->verVentasCajero($traID);
        }
        else {
            $this->showError404();
        }
    }
    
    public function verVentasCajero($traID){
        $data["titulo"] = "Ver ventas realizadas - Cajero";
        $data["resultado"] = $this->venta->getVentaCajero($traID);
        $data["contenido"] = "views/venta/venta_cajero.php";
        require_once TEMPLATE;
    }

    public function verVentasAdministrador() : void {
        $data["titulo"] = "Reportes de ventas - Administrador";
        $data["contenido"] = "views/venta/venta_administrador.php";
        require_once TEMPLATE;
    }

    public function verDetalleVenta(){
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $detalle = array();
            $detalle = $this->venta->getDetalleVenta($_POST["record_id"]);
            if(isset($detalle)){
                echo json_encode(["success"=>true,"detalle"=>$detalle]);
            }
            else{
                echo json_encode(["success"=>false]);
            }

        }
    }
    public function obtenerReporteTotalProductos(){
        $productosObtenidos = $this->venta->reportProducts();
        if ( isset($productosObtenidos)){
            echo json_encode(["success"=>true, "productos"=>$productosObtenidos]);
        }
        else{
            echo json_encode(["success"=>false, "mensaje"=>"No hay productos"]);
        }
    }


    public function generarComprobanteVenta($id){
        // Obtención de datos de la venta y detalles
        $datos_venta = $this->venta->getVentaID($id);
        $datosDetalleventa = $this->venta->getDetalleVenta($id);
        $nombreEmpresa = "D' LOLA RESTAURANTE CIX ©";
        $numeroventa = $id;
        $cliente = empty($datos_venta["NombreApellidoCliente"]) ? $datos_venta["TIPOCLIENTE"] : $datos_venta["NombreApellidoCliente"];
        $igv = 0.18;
        $total = 0;
    
        // Configuración del PDF
        $this->pdf->SetMargins(4, 10, 4);
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
    
        // Encabezado del comprobante
        $this->pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", strtoupper($nombreEmpresa)), 0, 'C', false);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "RUC: 1729278258"), 0, 'C', false);
        $this->pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Direccion: C. Teodoro Cárdenas 133, Lima 15046"), 0, 'C', false);
        $this->pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Teléfono: 955222600"), 0, 'C', false);
        $this->pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Email: DLOLARESTAURANTE@DLOLA.COM"), 0, 'C', false);
        $this->pdf->Ln(1);
    
        // Información de la venta
        $this->pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "VENTA N°: " . $numeroventa), 0, 'C', false);
        $this->pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Cliente: " . $cliente), 0, 'C', false);
        $this->pdf->Ln(1);
        $this->pdf->Cell(0, 5, iconv("UTF-8", "ISO-8859-1", "------------------------------------------------------"), 0, 0, 'C');
        $this->pdf->Ln(5);
        $this->pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Fecha: " . $datos_venta["Fecha"]), 0, 'C', false);
        $this->pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Venta Nro: " . $id), 0, 'C', false);
        $this->pdf->MultiCell(0, 5, iconv("UTF-8", "ISO-8859-1", "Cajero: " . $datos_venta["NOMBRE_APELLIDO_CAJERO"]), 0, 'C', false);
        $this->pdf->Ln(1);
    
        // Tabla de productos
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->Ln(1);
        $this->pdf->Cell(0, 5, iconv("UTF-8", "ISO-8859-1", "-------------------------------------------------------------------"), 0, 0, 'C');
        $this->pdf->Ln(3);
        $this->pdf->Cell(10, 5, iconv("UTF-8", "ISO-8859-1", "Cant."), 0, 0, 'C');
        $this->pdf->Cell(19, 5, iconv("UTF-8", "ISO-8859-1", "Precio"), 0, 0, 'C');
        $this->pdf->Cell(15, 5, iconv("UTF-8", "ISO-8859-1", "Nombre"), 0, 0, 'C');
        $this->pdf->Cell(28, 5, iconv("UTF-8", "ISO-8859-1", "Precio Final"), 0, 0, 'C');
        $this->pdf->Ln(3);
        $this->pdf->Cell(72, 5, iconv("UTF-8", "ISO-8859-1", "-------------------------------------------------------------------"), 0, 0, 'C');
        $this->pdf->Ln(5);
    
        foreach ($datosDetalleventa as $detalleventa) {
            $this->pdf->Cell(10, 4, iconv("UTF-8", "ISO-8859-1", $detalleventa["iDetCantidad"]), 0, 0, 'C');
            $this->pdf->Cell(19, 4, iconv("UTF-8", "ISO-8859-1", $detalleventa["cPlaPrecio"]), 0, 0, 'C');
            $this->pdf->Cell(19, 4, iconv("UTF-8", "ISO-8859-1", $detalleventa["cPlaNombre"]), 0, 0, 'C');
            $this->pdf->Cell(28, 4, iconv("UTF-8", "ISO-8859-1", $detalleventa["PrecioFinal"]), 0, 0, 'C');
            $this->pdf->Ln(4);
            $total += $detalleventa["PrecioFinal"];
        }            
        $this->pdf->Ln(1);
    
        // Resumen de la venta

        $this->pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

        $this->pdf->Ln(5);

        # Impuestos & totales #
        $this->pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $this->pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","OP. GRAVADAS"),0,0,'C');
        $this->pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","S/.".strval(floatval($total - ($total*$igv)))." PEN"),0,0,'C');

        $this->pdf->Ln(5);
        $this->pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $this->pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","IGV (18%)"),0,0,'C');
        $this->pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","S/.".strval(floatval($total*$igv))." PEN"),0,0,'C');

        $this->pdf->Ln(5);
        $this->pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $this->pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","SUBTOTAL"),0,0,'C');
        $this->pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","S/.".strval(number_format(floatval($total), 2, '.', ''))." PEN"),0,0,'C');

        $this->pdf->Ln(5);

        $this->pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');

        $this->pdf->Ln(5);

        $this->pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $this->pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","TOTAL A PAGAR"),0,0,'C');
        $this->pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","S/.".strval(number_format(floatval($total), 2, '.', ''))." PEN"),0,0,'C');

        $this->pdf->Ln(5);
        
        $this->pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $this->pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","TOTAL PAGADO"),0,0,'C');
        $this->pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1","S/.".strval(number_format(floatval($datos_venta["Total"]), 2, '.', ''))." PEN"),0,0,'C');

        $this->pdf->Ln(5);

        $this->pdf->Cell(18,5,iconv("UTF-8", "ISO-8859-1",""),0,0,'C');
        $this->pdf->Cell(22,5,iconv("UTF-8", "ISO-8859-1","CAMBIO"),0,0,'C');
        $this->pdf->Cell(32, 5, iconv("UTF-8", "ISO-8859-1", "S/." . strval(number_format(floatval($datos_venta["Total"]) - floatval($total), 2, '.', '')) . " PEN"), 0, 0, 'C');
        $this->pdf->Ln(9);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","*** Para poder realizar un reclamo o devolución debe de presentar este ticket ***"),0,'C',false);
        $this->pdf->SetFont('Arial','B',9);
        $this->pdf->Cell(0,7,iconv("UTF-8", "ISO-8859-1","Gracias por su compra"),'',0,'C');
        $this->pdf->Ln(9);
    
        # Codigo de barras #
        $this->pdf->Code128(5,$this->pdf->GetY(),"COD000001V000".strval($id),70,20);
        $this->pdf->SetXY(0,$this->pdf->GetY()+21);
        $this->pdf->SetFont('Arial','',14);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","COD000001V000".strval($id)),0,'C',false);
        $this->pdf->Output("I", "Venta_Nro_" . $id . ".pdf", true);
    }
    
    private function showError404() : void {
        if (defined('ERROR404')) {
            require_once ERROR404;
        } else {
            echo "Error 404: Página no encontrada";
        }
    }
}