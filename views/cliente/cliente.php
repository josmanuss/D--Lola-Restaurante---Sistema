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
                            <a href="" class="btn btn-danger"><span class="fas fa-file-pdf mr-2"></span>Generar reportes</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="btn btn-success" data-toggle="modal" data-target="#modalRegistroCliente">NUEVO REGISTRO</a>
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
                                <table class="table table-striped table-hover" id="tbl-Clientes">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>ID Cliente</th>
                                            <th>Nombres</th>
                                            <th>Apellidos</th>
                                            <th>Genero</th>
                                            <th>Pais</th>
                                            <th>Tipo</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ( $data["resultado"] as $Cliente) : ?>
                                        <tr>
                                            <td><?php echo $Cliente["cCliID"]; ?></td>
                                            <td><?php echo $Cliente["cPerNombre"]; ?></td>
                                            <td><?php echo $Cliente["cPerApellidos"]; ?></td>
                                            <td><?php echo $Cliente["cPerGenero"]; ?></td>
                                            <td><?php echo $Cliente["cPerPais"]; ?></td>
                                            <td><?php echo $Cliente["cCliTipoCliente"]; ?></td>
                                            <td>
                                                <a href="<?php echo $Cliente["cCliTipoCliente"] !== 'CLIENTE EN RESTAURANTE' ? 'index.php?c=ClienteController&a=verCliente&id=' . $Cliente["cCliID"] : '#'; ?>" 
                                                class="btn btn-warning <?php echo $Cliente["cCliTipoCliente"] === 'CLIENTE EN RESTAURANTE' ? 'disabled' : ''; ?>">
                                                    <i class="fas fa-user-edit"></i>
                                                </a>
                                                
                                                <a href="#" class="btn btn-danger deleteBtn" data-toggle="modal" data-target="#deleteModal" data-recordid="<?php echo $Cliente["cCliID"]; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </a>
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

<div class="modal fade" id="modalRegistroCliente" tabindex="-1" aria-labelledby="modalRegistroClienteLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalRegistroClienteLabel">Formulario de registro de nuevo cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="index.php?c=ClienteController&a=registrar" method="POST" id="registrarClienteForm" autocomplete="off" enctype="multipart/form-data">
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Nombres</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="txtNombres" value="<?php echo $_REQUEST["txtNombres"] ?? ''; ?>">
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Apellido</label>
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
                        <option value="">--SELECCIONE UN TIPO DE DOCUMENTO</option>
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
                    <?php if (isset($data["errores"]["correo"])) : ?>
                        <div class="text-danger">
                            <?php echo $data["errores"]["correo"]; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Género</label>
                <div class="col-sm-9">
                    <select class="form-control" name="selectGenero">
                        <option value="">--SELECCIONE UN GÉNERO--</option>
                        <option value="M" <?php echo (isset($_REQUEST["selectGenero"]) && $_REQUEST["selectGenero"] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="F" <?php echo (isset($_REQUEST["selectGenero"]) && $_REQUEST["selectGenero"] == 'F') ? 'selected' : ''; ?>>Femenino</option>
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
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                    <input type="submit" value="Registrar cliente" class="btn btn-block btn-success" name="btnEnviar">
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
    $(document).ready(function(){
        var formularioRegistro = $("#registrarClienteForm");            
        formularioRegistro.submit(function(event){
            var camposVaciosNombres = [];
            formularioRegistro.find('input[type="text"], input[type="password"], input[type="email"]').each(function(){
                if ($(this).val().trim() === ""){
                    camposVaciosNombres.push($(this).attr("name"));
                }
            });
            formularioRegistro.find('select').each(function(){
                if ($(this).val() === ""){
                    camposVaciosNombres.push($(this).attr("name"));
                }
            });
            if (camposVaciosNombres.length > 0){
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos vacíos',
                    html: 'Por favor, completa los siguientes campos:<br>' + camposVaciosNombres.join('<br>')
                });
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        const toggleAutoCompleteButton = $('#botonCompletador');
        const toggleAutoCompleteIcon = $('#toggleAutoCompleteIcon');
        const nombreClienteInput = $('[name="txtNCliente"]');
        const nombresInput = $('[name="txtNombres"]');
        const apellidoInput = $('[name="txtApellido"]');
        let isTextEmpty = true;
        toggleAutoCompleteButton.on('click', function() {
            if (isTextEmpty) {
                isTextEmpty = false;
                toggleAutoCompleteIcon.attr('class', 'fa fa-user-slash');
                nombreClienteInput.attr('readonly', true);
                const nombresValue = nombresInput.val().trim();
                const apellidoValue = apellidoInput.val().trim();
                if (!nombresValue && !apellidoValue) {
                    nombreClienteInput.attr("placeholder","COMPLETA LOS NOMBRES Y APELLIDOS PRIMERO");
                } 
                else if (!nombresValue) {
                    nombreClienteInput.attr("placeholder","COMPLETA LOS NOMBRES");
                }
                else if (!apellidoValue) {
                    nombreClienteInput.attr("placeholder","COMPLETA LOS APELLIDOS");
                }
                else {
                    nombreClienteInput.val(nombresValue + ' ' + apellidoValue);
                }
            } else {
                isTextEmpty = true;
                toggleAutoCompleteIcon.attr('class', 'fa fa-user');
                nombreClienteInput.removeAttr("placeholder");
                nombreClienteInput.removeAttr('readonly');
                nombreClienteInput.val("");
            }
        });
    });        
</script>


<script>
    $(document).ready(function() {
        $('.deleteBtn').on('click', function() {
            var userId = $(this).data(' ');
            var deleteUrl = 'index.php?c=ClienteController&a=eliminar&id=' + userId;
            $('#deleteRecordBtn').attr('href', deleteUrl);
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        const togglePasswordButton = $('#togglePassword');
        const toggleIcon = $('#toggleIcon');
        const passwordField = $('[name="txtContra"]');
        let isPasswordVisible = false;

        togglePasswordButton.on('click', function () {
            if (isPasswordVisible) {
                passwordField.attr('type', 'password');
                isPasswordVisible = false;
                toggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordField.attr('type', 'text');
                isPasswordVisible = true;
                toggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
    });
</script>
