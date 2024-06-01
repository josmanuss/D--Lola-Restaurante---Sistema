<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $data["titulo"]; ?></h3>
                </div>
                <div class="card-body">
                    <form action="index.php?c=ClienteController&a=actualizar" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">ID Cliente</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="idCliente" value="<?php echo $_REQUEST["idCliente"] ?? $data["consulta"]["cCliID"]; ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">ID Persona</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="idPersona" value="<?php echo $_REQUEST["idPersona"] ?? $data["consulta"]["cPerID"]; ?>" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Nombres</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtNombres" value="<?php echo $_REQUEST["txtNombres"] ?? $data["consulta"]["cPerNombre"]; ?>">
                                <?php if (isset($data["errores"]["nombres"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["nombres"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Apellido</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtApellido" value="<?php echo $_REQUEST["txtApellido"] ?? $data["consulta"]["cPerApellidos"]; ?>">
                                <?php if (isset($data["errores"]["apellido"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["apellido"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Fecha de nacimiento</label>
                            <div class="col-sm-9">
                                <input type="date" class="form-control" name="fechaNacimiento" value="<?php echo $data["consulta"]["cPerEdad"]; ?>">
                                <?php if (isset($data["errores"]["fechaNacimiento"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["fechaNacimiento"]; ?>
                                    </div>
                                <?php endif; ?>
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
                                <?php if (isset($data["errores"]["tipo_documento"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["tipo_documento"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <script>
                            const comboBox = document.getElementsByName("cbTipoDoc")[0];
                            console.log(comboBox);
                            var valorTipoDoc = '<?php echo $data["consulta"]["iTipoDocID"];?>';
                            for (var i = 0; i < comboBox.options.length; i++) {
                                if (valorTipoDoc == comboBox.options[i].value) {
                                    comboBox.selectedIndex = i;
                                    comboBox.focus();
                                    break;
                                }
                            }
                        </script>


                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Numero de documento</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtDNI" value="<?php echo $_REQUEST["txtDNI"] ?? $data["consulta"]["tPerNumDoc"]; ?>">
                                <?php if (isset($data["errores"]["numero_documento"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["numero_documento"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Correo</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" name="txtEmail" value="<?php echo $_REQUEST["txtEmail"] ?? $data["consulta"]["cPerCorreo"]; ?>">
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
                                <select class="form-control" name="selectGenero" id="selectGenero">
                                    <option value="<?php echo $_REQUEST["selectGenero"] ?? ''; ?>">--SELECCIONE UN GÉNERO--</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                </select>
                                
                                <script>
                                    const selectGenero = document.getElementsByName("selectGenero")[0];
                                    const valorGenero = "<?php echo $data["consulta"]["cPerGenero"]?>";
                                    for (var i = 0; i < selectGenero.options.length; ++i) {
                                        if ( valorGenero === selectGenero.options[i].value ){
                                            selectGenero.selectedIndex = i;
                                            selectGenero.focus();
                                            break;
                                        }
                                    }
                                </script>

                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">País</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtPais" value="<?php echo $_REQUEST["txtPais"] ?? $data["consulta"]["cPerPais"]; ?>">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Habilitado</label>
                            <div class="col-sm-9">
                                <input type="checkbox" class="form-control" name="chkHabilitado" id ="chkHabilitado" value="<?php echo $data["consulta"]["cCliHabilitado"];?>">
                                <?php if (isset($data["errores"]["habilitado"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["habilitado"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <script type="text/javascript">
                            const chkHabilitado = document.getElementById("chkHabilitado");
                            //const habilitado = parseInt("<?php echo $data["consulta"]["cCliHabilitado"];?>");
                            if ( chkHabilitado.value == parseInt(1) ){
                                chkHabilitado.checked = true;
                            }
                            else{
                                chkHabilitado.checked = false;
                            }
                        </script>

                        <div class="mb-3 row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <input type="submit" value="Actualizar cliente" class="btn btn-block btn-success" name="btnEnviar">
                                <button type="button" class="btn btn-block btn-secondary mb-3" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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