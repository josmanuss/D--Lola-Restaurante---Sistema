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
        if ($carID == 1) {
            $this->verVentasAdministrador();
        } else {
            $this->showError404();
        }
        
    }

    



    
    public function verVentasAdministrador() : void {
        $data["titulo"] = "Reportes de ventas - Administrador";
        $data["resultado"] = $this->venta->getVentas();
        $data["contenido"] = "views/venta/venta_administrador.php";
        require_once TEMPLATE;
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


    public function generarTicketVenta($id) {
        $datos_venta = $this->venta->getVentaID($id);
        $datosDetalleVenta = $this->venta->getDetalleVenta($id);
        $nombre_empresa = "D' Lola Restaurante ©";
        $numero_venta = $id;
        $cliente = $datos_venta["cliente"];
        $total = 0;
        foreach($datosDetalleVenta as $detalleArray) {
            $total += $detalleArray["iDetCantidad"] * $detalleArray["cPlaPrecio"];
        }
        $total_pagado = $datos_venta["fPedTotal"];
        $codigo_barras = "COD00000V000".strval($id);
        $this->pdf->SetMargins(4,10,4);
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial','B',10);
        $this->pdf->SetTextColor(0,0,0);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1", strtoupper($nombre_empresa)),0,'C',false);
        $this->pdf->SetFont('Arial','',9);
        $this->pdf->Ln(1);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1", "TICKET - VENTA N°: " . $numero_venta),0,'C',false);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1", "Cliente: " . $cliente),0,'C',false);
        $this->pdf->Ln(1);


        $this->pdf->Ln(1);
        $this->pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","------------------------------------------------------"),0,0,'C');
        $this->pdf->Ln(5);

        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Fecha: ".date("d/m/Y")),0,'C',false);
        //$this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Caja Nro: 1"),0,'C',false);
        //$this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1","Cajero: Carlos Alfaro"),0,'C',false);
        $this->pdf->Ln(1);
        $this->pdf->Ln(1);
        $this->pdf->SetFont('Arial','B',10);
        $this->pdf->SetFont('Arial','',9);

            # Tabla de productos #
        $this->pdf->Cell(10,5,iconv("UTF-8", "ISO-8859-1","Cant."),0,0,'C');
        $this->pdf->Cell(19,5,iconv("UTF-8", "ISO-8859-1","Precio"),0,0,'C');
        $this->pdf->Cell(15,5,iconv("UTF-8", "ISO-8859-1","Desc."),0,0,'C');
        $this->pdf->Cell(28,5,iconv("UTF-8", "ISO-8859-1","Total"),0,0,'C');
        $this->pdf->Ln(3);
        $this->pdf->Cell(72,5,iconv("UTF-8", "ISO-8859-1","-------------------------------------------------------------------"),0,0,'C');
        $this->pdf->Ln(3);
    /*----------  Detalles de la tabla  ----------*/
        $this->pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1","Nombre de producto a vender"),0,'C',false);
        $this->pdf->Cell(10,4,iconv("UTF-8", "ISO-8859-1","7"),0,0,'C');
        $this->pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1","$10 USD"),0,0,'C');
        $this->pdf->Cell(19,4,iconv("UTF-8", "ISO-8859-1","$0.00 USD"),0,0,'C');
        $this->pdf->Cell(28,4,iconv("UTF-8", "ISO-8859-1","$70.00 USD"),0,0,'C');
        $this->pdf->Ln(4);
        $this->pdf->MultiCell(0,4,iconv("UTF-8", "ISO-8859-1","Garantía de fábrica: 2 Meses"),0,'C',false);
        $this->pdf->Ln(7);
    /*----------  Fin Detalles de la tabla  ----------*/

        // Mostrar el total pagado y el cambio
        $cambio = $total_pagado - $total;
        $this->pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","TOTAL PAGADO"),0,0,'C');
        $this->pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",'$'.number_format($total_pagado,2)),0,0,'C');
        $this->pdf->Ln(5);
        $this->pdf->Cell(0,5,iconv("UTF-8", "ISO-8859-1","CAMBIO"),0,0,'C');
        $this->pdf->Cell(32,5,iconv("UTF-8", "ISO-8859-1",'$'.number_format($cambio,2)),0,0,'C');
        $this->pdf->Ln(5);
    
        // Más código ...
    
        # Codigo de barras #
        $this->pdf->Code128(5,$this->pdf->GetY(),$codigo_barras,70,20);
        $this->pdf->SetXY(0,$this->pdf->GetY()+21);
        $this->pdf->SetFont('Arial','',14);
        $this->pdf->MultiCell(0,5,iconv("UTF-8", "ISO-8859-1",$codigo_barras),0,'C',false);
        
        # Nombre del archivo PDF #
        $this->pdf->Output("I","Ticket_Nro_".$id.".pdf",true);
    }   
    

    private function showError404() : void {
        if (defined('ERROR404')) {
            require_once ERROR404;
        } else {
            echo "Error 404: Página no encontrada";
        }
    }
}