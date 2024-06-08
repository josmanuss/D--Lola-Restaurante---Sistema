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
                            <a href="" class="btn btn-success" data-toggle="modal" data-target="#modalRegistroPlato">NUEVO REGISTRO</a>
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
                            <table class="table table-striped table-hover" id="tbl-Platos">
                                <thead class="thead-dark">
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
                                    <?php foreach ($data["resultado"] as $platos) : ?>
                                        <tr>
                                            <td><?php echo $platos["cPlaID"]; ?></td>                             
                                            <td><?php echo $platos["cCatID"]; ?></td>
                                            <td><?php echo $platos["cPlaNombre"]; ?></td>
                                            <td><?php echo $platos["cPlaCantidad"]; ?></td>
                                            <td><?php echo "S/.".$platos["cPlaPrecio"]; ?></td>
                                            <td>
                                                <a href="#" class="btn btn-xs btn-warning"><i class="fas fa-user-edit"></i></a>
                                                <a href="#" class="btn btn-xs btn-danger deleteBtn" data-toggle="modal" data-target="#deleteModal" data-recordid="<?php echo $platos["cCatID"]; ?>"><i class="fas fa-trash"></i></a>
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
</div>

<div class="modal fade" id="modalRegistroPlato" tabindex="-1" aria-labelledby="modalRegistroPlatoLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRegistroPlatoLabel">Formulario de registro de plato nuevo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Categorias</label>
                <div class="col-sm-9">
                    <select class="form-control" name="categoriaPlato">
                        <option value="<?php echo $_REQUEST["categoriaPlato"] ?? ''; ?>">SELECCIONE UNA CATEGORIA</option>
                        <?php foreach ( $data["categorias"] as $categoria ): ?>
                            <option value="<?=$categoria["cCatNombre"]?>"><?php echo $categoria["cCatNombre"] ?></option>
                        <?php endforeach; ?>
                    </select>    
                    <?php if (isset($data["errores"]["nombres"])) : ?>
                        <div class="text-danger">
                            <?php echo $data["errores"]["nombres"]; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Nombres</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="txtNombres" value="<?php echo $_REQUEST["txtNombres"] ?? ''; ?>">
                    <?php if (isset($data["errores"]["nombres"])) : ?>
                        <div class="text-danger">
                            <?php echo $data["errores"]["nombres"]; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Precio</label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" name="spinnerPrecio" value="<?php echo $_REQUEST["spinnerPrecio"] ?? ''; ?>">
                    <?php if (isset($data["errores"]["spinnerPrecio"])) : ?>
                        <div class="text-danger">
                            <?php echo $data["errores"]["spinnerPrecio"]; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Cantidad</label>
                <div class="col-sm-9">
                    <input type="number" class="form-control" name="spinnerCantidad" value="<?php echo $_REQUEST["spinnerCantidad"] ?? ''; ?>">
                    <?php if (isset($data["errores"]["spinnerCantidad"])) : ?>
                        <div class="text-danger">
                            <?php echo $data["errores"]["spinnerCantidad"]; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Descripcion</label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="txtDescripcion" value="<?php echo $_REQUEST["txtDescripcion"] ?? ''; ?>"></textarea>
                    <?php if (isset($data["errores"]["descripcionPlato"])) : ?>
                        <div class="text-danger">
                            <?php echo $data["errores"]["descripcionPlato"]; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <input type="submit" value="Registrar Plato" class="btn btn-block btn-success" name="btnEnviar">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('.deleteBtn').on('click', function() {
            var userId = $(this).data(' ');
            var deleteUrl = 'index.php?c=CategoriaController&a=eliminar&id=' + userId;
            $('#deleteRecordBtn').attr('href', deleteUrl);
        });
    });

    function actualizarTabla() {
        $.ajax({
            url: 'index.php?c=PlatoController&a=getPlatoJSON',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $.each(data, function(index, plato) {
                    var filaExistente = $('#tbl-Platos tbody tr[data-id="' + plato.cPlaID + '"]');
                    if (filaExistente.length) {
                        filaExistente.find('td:eq(1)').text(plato.cCatID);
                        filaExistente.find('td:eq(2)').text(plato.cPlaNombre);
                        filaExistente.find('td:eq(3)').text(plato.cPlaCantidad);
                        filaExistente.find('td:eq(4)').text('S/.' + plato.cPlaPrecio);
                    } else {
                        var fila = '<tr data-id="' + plato.cPlaID + '">' +
                            '<td>' + plato.cPlaID + '</td>' +
                            '<td>' + plato.cCatID + '</td>' +
                            '<td>' + plato.cPlaNombre + '</td>' +
                            '<td>' + plato.cPlaCantidad + '</td>' +
                            '<td>' + 'S/.' + plato.cPlaPrecio + '</td>' +
                            '<td>' +
                            '<a href="#" class="btn btn-xs btn-warning"><i class="fas fa-user-edit"></i></a>' +
                            '<a href="#" class="btn btn-xs btn-danger deleteBtn" data-toggle="modal" data-target="#deleteModal" data-recordid="' + plato.cCatID + '"><i class="fas fa-trash"></i></a>' +
                            '</td>' +
                            '</tr>';

                        $('#tbl-Platos tbody').append(fila);
                    }
                });
            }
        });
    }

    // $(document).ready(function() {
    //     actualizarTabla();
    //     setInterval(actualizarTabla, 2000); // Cambia este valor según la frecuencia de actualización deseada
    // });
</script>
