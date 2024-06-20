<style>
    .btn {
        margin-right: 10px;
        padding: 8px 12px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn:hover {
        opacity: 0.8;
    }

    .btn i {
        margin-right: 5px;
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    /* Estilos para tablas */
    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 8px;
        border: 1px solid #ddd;
    }

    .table th {
        background-color: #f2f2f2;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Otros estilos */
    .float-right {
        float: right;
    }

    .mr-1 {
        margin-right: 10px;
    }

    .mr-4 {
        margin-right: 20px;
    }
</style>


<div class="content-wrapper">
    <div class ="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <h1 class="m-0"><?php echo $data["titulo"];?></h1>
                </div>
            </div>
        </div>
    </div>
    
    <div class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="table table-responsive">
                    <table class="table" id="tbl-PedidosAdmin">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID Pedido</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data["pedido"] as $pedido) : ?>
                            <tr>
                                <td><?php echo $pedido["ID_Pedido"]; ?></td>
                                <?php if (empty($pedido["NombreApellidoCliente"])) : ?>
                                    <td><?php echo $pedido["TipoCliente"]; ?></td>
                                <?php else : ?>
                                    <td><?php echo $pedido["NombreApellidoCliente"]; ?></td>
                                <?php endif; ?>
                                <td><?php echo $pedido["Fecha"]; ?></td>
                                <td><?php echo $pedido["Total"]; ?></td>
                                <td>
                                    <button class="btn btn-success payBtn" data-recordid="<?php echo $pedido["ID_Pedido"]; ?>"><i class="fas fa-money-check-alt"></i>Pagar</button>
                                    <a href="#" class="btn btn-warning detailBtn" data-recordid="<?php echo $pedido["ID_Pedido"]; ?>"><i class="fas fa-book"></i>Ver detalle</a>
                                    <a href="index.php?c=PedidoController&a=generarTicketOrden&id=<?php echo $pedido["ID_Pedido"]; ?>" target="_blank" class="btn btn-danger pdfBtn"><i class="fas fa-file-pdf"></i>Descargar en pdf</a>
                                    <a href="#" class="btn btn-danger eliminarBtn"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tablaModal" tabindex="-1" role="dialog" aria-labelledby="tablaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title m-2 text-center" id="tablaModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabla-detalle">
                            <thead class="thead-dark">
                            <tr>
                                <th>ID Pedido</th>
                                <th>ID Plato</th>
                                <th>Categoria</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="views/venta/js/funciones3.js"></script>