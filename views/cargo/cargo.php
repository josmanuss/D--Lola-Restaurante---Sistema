<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <h1 class="m-0"><?php echo $data["titulo"]; ?></h1>
                </div>
                <div class="col-sm-4">
                    <?php if (isset($_SESSION["mensaje"])) : ?>
                        <div id="alert-msj" class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong><?php echo $_SESSION["mensaje"]; ?></strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <script>
                            setTimeout(function() {
                                $('#alert-msj').fadeOut('fast');
                            }, 3000);
                        </script>
                        <?php unset($_SESSION["mensaje"]);
                    endif; ?>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="" class="btn btn-success" data-toggle="modal" data-target="#modalRegistroCargo">NUEVO REGISTRO</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tbl-Cargos">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombre de cargo</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($data["resultado"] as $i => $cargos) : ?>
                                        <tr>
                                            <td><?php echo $cargos["iCarID"]; ?></td>                             
                                            <td><?php echo $cargos["tCarNombre"]; ?></td>
                                            <td>
                                                <a href="#" class="btn btn-warning botonActualizar" data-toggle="modal" data-target="#modalActualizarCargo" data-index="<?php echo $i; ?>"><i class="fas fa-user-edit"></i></a>
                                                <a href="#" class="btn btn-danger deleteBtn" data-toggle="modal" data-target="#deleteModal" data-recordid="<?php echo $cargos["iCarID"]; ?>"><i class="fas fa-trash"></i></a>
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
        </div>
    </div>
    <div class="modal fade" id="modalActualizarCargo" tabindex="-1" role="dialog" aria-labelledby="modalActualizarCargoLabel" aria-hidden="true">    
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalActualizarPlatoLabel">Formulario de Actualizar Cargo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php?c=CargoController&a=actualizar" method="POST" autocomplete="off" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">ID Consultado</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="idCargo" name="idCargo" value="" readonly>
                        </div>
                    </div>    
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Nombres</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="nombreCargo" name="nombreCargo" value="<?php echo $_REQUEST["nombreCargo"] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <input type="submit" value="Actualizar Cargo" class="btn btn-block btn-success" name="btnEnviar">
                            <button type="button" class="btn btn-block btn-secondary mb-3" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>    

    <div class="modal fade" id="modalRegistroCargo" tabindex="-1" role="dialog" aria-labelledby="modalRegistroCargoLabel" aria-hidden="true">    
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistroPlatoLabel">Formulario de registro de nuevo cargo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="index.php?c=CargoController&a=registrarCargo" method="POST" autocomplete="off" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Nombres</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="nombreCargo" value="<?php echo $_REQUEST["nombreCargo"] ?? ''; ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <input type="submit" value="Registrar Cargo" class="btn btn-block btn-success" name="btnEnviar">
                            <button type="button" class="btn btn-block btn-secondary mb-3" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>    

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar este registro?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <a id="deleteRecordBtn" href="#" class="btn btn-danger">Eliminar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('.botonActualizar').on('click', function() {
            let cargo = <?php echo json_encode($data["resultado"]); ?>;
            var indice = $(this).data('index');
            $("#idCargo").val(cargo[indice]["iCarID"]);
            $("#nombreCargo").val(cargo[indice]["tCarNombre"]);    
        });

        $('.deleteBtn').on('click', function() {
            var cargoId = $(this).data('recordid');
            var deleteUrl = 'index.php?c=CargoController&a=eliminarCargo&id=' + cargoId;
            $('#deleteRecordBtn').attr('href', deleteUrl);
        });
    });
</script>

