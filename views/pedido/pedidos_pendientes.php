<style>
    .btn {
        margin-right: 10px;
    }

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

    .btn-danger, .btn-primary {
        padding: 8px 12px; 
        border: none; 
        border-radius: 5px; 
        cursor: pointer; 
    }

    .btn-danger:hover, .btn-primary:hover {
        opacity: 0.8; 
    }

    .btn-danger i, .btn-primary i {
        margin-right: 5px; 
    }

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


<div class="content-wrapper" id="contenidoAdmin">
    <div class ="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-5">
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
                    <table class="table" id="tbl-VentasAdmin">
                        <thead>
                            <tr>
                                <th>ID Pedido</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($data["pedido"])): ?>
                            <?php foreach ($data["pedido"] as $pedidos): ?>
                                <?php if ($pedidos["Estado"] === 'PENDIENTE'): ?>
                                    <tr>
                                        <td><?php echo $pedidos["ID_Pedido"]; ?></td>
                                        <td><?php echo !empty($pedidos["NombreApellidoCliente"]) ? $pedidos["NombreApellidoCliente"] : $pedidos["TipoCliente"]; ?></td>
                                        <td><?php echo $pedidos["Fecha"]; ?></td>
                                        <td><?php echo $pedidos["Total"]; ?></td>
                                        <td>
                                            <a href="#" class="btn btn-danger deleteBtn" data-toggle="modal" data-target="#deleteModal" data-recordid="<?php echo $pedidos["ID_Pedido"]; ?>"><i class="fas fa-trash"></i> Eliminar pedido</a>
                                            <a href="index.php?c=PedidoController&a=generarTicketOrden&id=<?php echo $pedidos["ID_Pedido"]; ?>" target="_blank" class="btn btn-danger pdfBtn"><i class="fas fa-file-pdf"></i> Descargar comanda</a>
                                            <a href="index.php?c=PedidoController&a=enviarPedidoCaja&id=<?php echo $pedidos["ID_Pedido"]; ?>" class="btn btn-primary enviarCaja"><i class="fas fa-arrow-right"></i>Enviar a caja</a>
                                            <a href="#" class="btn btn-primary btnDetail" data-toggle="modal" data-target="#tablaModal" data-recordid="<?php echo $pedidos["ID_Pedido"]; ?>"><i class="fas fa-book"></i></a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>  
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
                    <h5 class="modal-title" id="tablaModalLabel">Tabla de Productos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="tabla-detalle">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Categoria</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="views/pedido/js/funciones3.js"></script>