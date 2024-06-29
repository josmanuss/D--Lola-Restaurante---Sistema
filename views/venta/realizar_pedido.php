<style>
    .content-wrapper {
        padding: 20px;
    }
    .card {
        margin-bottom: 20px;
    }
    .form-group {
        margin-bottom: 15px;
    }

    .card-header {
        background-color: #f8f9fa;
    }

    .btn {
        cursor: pointer;
    }

    .table {
        width: 100%;
    }

    .table thead th {
        vertical-align: middle;
        text-align: center;
    }

    .table tbody td {
        vertical-align: middle;
        text-align: center;
    }

    .table tbody input[type="number"] {
        width: 70px;
        text-align: center;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .btn-danger:hover {
        background-color: #c82333;
        border-color: #bd2130;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }
        
    .btn-disabled {
        opacity: 0.5;
    }

    datalist {
        width: 100%; 
    }


    datalist option {
        background-color: #fff; 
        color: #333; 
        padding: 5px; 
        cursor: pointer; 
    }

    datalist option:hover {
        background-color: #f0f0f0; 
    }

</style>


<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <input type="hidden" class="mesa" value="<?php echo $id;?>">
                    <h1 class="m-0"><?php echo $data["titulo"];?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-0">Cliente:</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="tipoCliente">TIPO DE CLIENTE</label>
                                    <select class="form-control" id="tipocliente">
                                        <option value="1">IDENTIFICADO</option>
                                        <option value="0">CLIENTE EN RESTAURANTE</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="DNI">DNI Cliente:</label>
                                    <input type="text" class="form-control" name="dniSearch" list="dni">
                                    <datalist id="dni" class="list-unstyled border rounded">
                                        <?php foreach ($data["dni"] as $dni ): ?>
                                        <option value="<?php echo $dni["DNI"];?>"><?php echo $dni["DNI"];?></option>
                                        <?php endforeach;?>
                                    </datalist>
                                </div>
                                <div class="col-md-3">
                                    <label for="ID">ID Cliente:</label>
                                    <input type="text" class="form-control" name="idCliente" readonly>
                                </div>
                                <input type="hidden" name="mozo" id="mozo" class="mozo" value="<?php echo $_SESSION["trabajador"]["cTraID"];?>">
                                <div class="col-md-3">
                                    <label for="NOMAP">Nombres y apellidos</label>
                                    <input type="text" class="form-control" name="nombresApellidos" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="m-0">Informacion del pedido</h5>
                        </div>
                        <div class="card-body">
                            <label>Platos a buscar:</label>
                            <div id="autocomplete-container">
                                <input type="search" class="form-control col-lg-12 mb-2" placeholder="Ingrese el nombre del plato:" id="buscarPlato">
                                <div id="autocomplete-results"></div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tabla-productosvender">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>ID Categoria</th>
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
                                    <button class="btn btn-primary btn-block" id="enviarPedido">Registrar pedido</button>
                                    <button class="btn btn-danger btn-block" id="vaciarTabla">Vaciar tabla</button>
                                </div>
                            </div>
                        </div>
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
<script type="text/javascript" src="views/venta/js/funciones1.js"></script>
<script type="text/javascript" src="views/venta/js/funciones2.js"></script>




