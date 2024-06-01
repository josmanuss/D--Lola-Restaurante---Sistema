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
                            <a href="" class="btn btn-danger"><i class="fas fa-file-pdf mr-1"></i>Generar reportes</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="btn btn-success" data-toggle="modal" data-target="#modalRegistroTrabajador">NUEVO REGISTRO</a>
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
                                <table class="table table-striped table-hover" id="tbl-Trabajador">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Num. Doc</th>
                                            <th>Correo</th>
                                            <th>Genero</th>
                                            <th>Pais</th>
                                            <th>Sueldo</th>
                                            <th>Rol</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ( $data["resultado"] as $i => $trabajador ) : ?>
                                        <tr>
                                        <td><?php echo $trabajador["cUserID"]; ?></td>
                                        <td><?php echo $trabajador["cPerNombre"]; ?></td>
                                        <td><?php echo $trabajador["cPerApellidos"]; ?></td>
                                        <td><?php echo $trabajador["tPerNumDoc"]; ?></td>
                                        <td><?php echo $trabajador["cPerCorreo"]; ?></td>
                                        <td><?php echo $trabajador["cPerGenero"]; ?></td>
                                        <td><?php echo $trabajador["cPerPais"]; ?></td>
                                        <td><?php echo $trabajador["fTraSueldo"] ?></td>
                                        <td><?php echo $trabajador["cUserRol"]; ?></td>
                                        <td>
                                            <a href="index.php?c=TrabajadorController&a=verTrabajador&id=<?php echo $trabajador["cUserID"];?>" class="btn btn-xs btn-warning updateBtn"><i class="fas fa-user-edit"></i></a>
                                            <a href="" class="btn btn-xs btn-danger deleteBtn" data-toggle="modal" data-target="#deleteModal" data-recordid="<?php echo $trabajador["cTraID"]; ?>"><i class="fas fa-trash"></i></a>
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


<div class="modal fade" id="modalRegistroTrabajador" tabindex="-1" aria-labelledby="modalRegistroTrabajadorLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRegistroTrabajadorLabel">Formulario de registro de nuevo trabajador</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="index.php?c=TrabajadorController&a=registrar" method="POST" autocomplete="off" enctype="multipart/form-data" id="registroTrabajadorForm">
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Nombres</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="txtNombres" value="<?php echo $_REQUEST["txtNombres"] ?? ''; ?>">

                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Apellido paterno</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="txtApellido" value="<?php echo $_REQUEST["txtApellido"] ?? ''; ?>">

                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Fecha de nacimiento</label>
                <div class="col-sm-9">
                    <input type="date" class="form-control" name="fechaNacimiento" value="<?php echo $_REQUEST["fechaNacimiento"] ?? ''; ?>">

                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Tipo de documento</label>
                <div class="col-sm-9">
                    <select class="form-control" name="cbTipoDoc">
                        <option value="<?php echo $_REQUEST["cbTipoDoc"] ?? ''; ?>">--SELECCIONE UN TIPO DE DOCUMENTO</option>
                        <?php foreach ( $data["tipoDocumento"] as $tipoDoc ) :?>
                            <option value="<?= $tipoDoc["iTipoDocID"]?>"><?php echo $tipoDoc["tTipoDocNombre"];?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Numero de documento</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="txtDNI" value="<?php echo $_REQUEST["txtDNI"] ?? ''; ?>">

                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Correo</label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" name="txtEmail" value="<?php echo $_REQUEST["txtEmail"] ?? ''; ?>">

                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Género</label>
                <div class="col-sm-9">
                    <select class="form-control" name="selectGenero">
                        <option value="<?php echo $_REQUEST["selectGenero"] ?? ''; ?>">--SELECCIONE UN GÉNERO--</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>

                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">País</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="txtPais" value="<?php echo $_REQUEST["txtPais"] ?? ''; ?>">

                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Cargo</label>
                <div class="col-sm-9">
                    <select class="form-control" name="selectCargo">
                        <option value="">--SELECCIONE UN CARGO--</option>
                        <?php foreach ($data["cargo"] as $cargo): ?>
                            <option value="<?= $cargo["iCarID"] ?>"><?= $cargo["tCarNombre"] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Nombre de usuario</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" class="form-control" name="txtNUsuario" value="<?php echo $_REQUEST["txtNUsuario"] ?? ''; ?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="botonCompletador">
                                <span id="toggleAutoCompleteIcon" class="fa fa-eye"></span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Contraseña</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="password" class="form-control" name="txtContra" value="<?php echo $_REQUEST["txtContra"] ?? '';?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <span id="toggleIcon" class="fa fa-eye"></span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Sueldo</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="number" class="form-control" name="numberSueldo" value="<?php echo $_REQUEST["numberSueldo"] ?? '';?>">
                    </div>

                </div>
            </div>
            <div class="mb-3 row">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <input type="submit" value="Registrar Trabajador" class="btn btn-block btn-success" name="btnEnviar">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        function validarFormulario(formulario) {
            formulario.submit(function(event) {
                var camposVaciosNombres = [];
                formulario.find('input[type="text"], input[type="password"], input[type="email"], input[type="hidden"], select').each(function() {
                    if ($(this).val().trim() === "") {
                        camposVaciosNombres.push($(this).attr("name"));
                    }
                });
                if (camposVaciosNombres.length > 0) {
                    event.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos vacíos',
                        html: 'Por favor, completa los siguientes campos:<br>' + camposVaciosNombres.join('<br>')
                    });
                }
            });
        }
        var formularioRegistro = $("#registroTrabajadorForm");
        validarFormulario(formularioRegistro);



    });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        const toggleAutoCompleteButton = $('#botonCompletador');
        const toggleAutoCompleteIcon = $('#toggleAutoCompleteIcon');
        const nombreUsuarioInput = $('[name="txtNUsuario"]');
        const nombresInput = $('[name="txtNombres"]');
        const apellidoInput = $('[name="txtApellido"]');
        const togglePasswordButton = $('#togglePassword');
        const toggleIcon = $('#toggleIcon');
        const passwordField = $('[name="txtContra"]');
        
        let isTextEmpty = true;
        let isPasswordVisible = false;

        toggleAutoCompleteButton.on('click', function() {
            isTextEmpty = !isTextEmpty;
            toggleAutoCompleteIcon.toggleClass('fa-user fa-user-slash');
            nombreUsuarioInput.attr('readonly', !isTextEmpty);
            if (isTextEmpty) {
                nombreUsuarioInput.removeAttr("placeholder").val("");
            } else {
                const nombresValue = nombresInput.val().trim();
                const apellidoValue = apellidoInput.val().trim();
                if (!nombresValue && !apellidoValue) {
                    nombreUsuarioInput.attr("placeholder", "COMPLETA LOS NOMBRES Y APELLIDOS PRIMERO");
                } else if (!nombresValue) {
                    nombreUsuarioInput.attr("placeholder", "COMPLETA LOS NOMBRES");
                } else if (!apellidoValue) {
                    nombreUsuarioInput.attr("placeholder", "COMPLETA LOS APELLIDOS");
                } else {
                    nombreUsuarioInput.val(`${nombresValue} ${apellidoValue}`);
                }
            }
        });

        togglePasswordButton.on('click', function () {
            isPasswordVisible = !isPasswordVisible;
            passwordField.attr('type', isPasswordVisible ? 'text' : 'password');
            toggleIcon.toggleClass('fa-eye fa-eye-slash');
        });
    });

</script>


<script type="text/javascript">
    $('.deleteBtn').on('click', function() {
        var userId = $(this).data('recordid');
        var deleteUrl = 'index.php?c=TrabajadorController&a=eliminar&id=' + userId;
        $('#deleteRecordBtn').attr('href', deleteUrl);
    });    
</script>