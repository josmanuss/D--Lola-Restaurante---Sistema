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
                    <table class="table">
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
                            <?php if (!empty($data["pedido"])) : ?>
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
                                            <a href="" class="btn btn-warning actPedidoBtn" data-toggle="modal" data-target="#pedidoModal" data-id-pedido="<?php echo $pedido["ID_Pedido"];?>"><i class="fas fa-user-edit"></i></a>
                                            <a href="#" class="btn btn-primary retornarBtn"><i class="fas fa-arrow-right"></i></a>
                                                                                        
                                        </td>
                                    </tr>
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

    <div class="modal fade" id="pedidoModal" tabindex="-1" role="dialog" aria-labelledby="pedidoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="pedidoModalLabel">Información del Pedido</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="container-fluid">
            <!-- Información del Pedido -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="m-0 titulo-pedido"></h5>
                            </div>
                            <div class="card-body">
                                <label>Platos a buscar:</label>
                                <div id="autocomplete-container">
                                    <input type="search" class="form-control mb-2" placeholder="Ingrese el nombre del plato:" id="buscarPlato">
                                    <div id="autocomplete-results"></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="tabla-productosactualizar">
                                    <thead class="thead-light">
                                        <tr>
                                        <th>ID</th>
                                        <th>Categoría</th>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    </table>
                                    <div class="form-group">
                                        <button class="btn btn-danger btn-block" id="vaciarTabla-actualizar">Vaciar tabla</button>
                                    </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Fin Información del Pedido -->
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
<script type="text/javascript">
    $(document).ready(function(){
        $(".actPedidoBtn").each(function(){
            $(this).on("click", function(){
                const id_pedido = $(this).data("id-pedido");
                $.ajax({
                    url: "index.php?c=PedidoController&a=verDetallePedido",
                    method: "POST",
                    data: { record_id: id_pedido },
                    async: true,
                    success: function(response){
                    var respuesta = JSON.parse(response);
                    if (respuesta.success){
                        $(".titulo-pedido").text("Informacion del pedido "+id_pedido);
                        var platos = respuesta.detalle;
                        var tbody = $('#tabla-productosactualizar tbody');
                        tbody.empty();
                        $.each(platos, function(index, plato){
                            var fila = '<tr>' +
                                '<form id="pedido" method="POST">' +
                                    '<td>' + plato["IDPedido"] + '</td>' +
                                    '<td>' + plato["Categoria"] + '</td>' +
                                    '<td>' + plato["NombrePlato"] + '</td>' +
                                    '<td><input type="number" class="form-control" value="' + plato["Cantidad"] + '"></td>' + 
                                    '<td>' + plato["PrecioFinal"] + '</td>' +
                                    '<td>' +
                                        '<a class="btn btn-xs btn-warning" id="actualizarCantidad"><i class="fas fa-user-edit mr-2"></i></a>'+ 
                                        '<a class="btn btn-xs btn-danger" id="eliminarDetalle"><i class="fas fa-trash mr-2"></i></a>'+ 
                                    '</td>' + 
                                '</form>' +
                            '</tr>';
                            tbody.append(fila);
                        });

                        $('input[type="number"]', tbody).last().on('input', function() {
                            var valor = parseFloat($(this).val());
                            if (valor < 0 || isNaN(valor)) {
                                $(this).val(0);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Alerta',
                                    text: 'No se puede indicar una cantidad menor a 0'
                                });
                            }
                        });
                    }
                }
                });
            });
        });
    });
</script>


<script type="text/javascript" src="views/venta/js/funciones5.js"></script>
<script type="text/javascript" src="views/venta/js/funciones2.js"></script>

