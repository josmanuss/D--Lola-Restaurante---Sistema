<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $data["titulo"]; ?></h3>
                </div>
                <div class="card-body">
                <form action="index.php?c=TrabajadorController&a=actualizar" method="POST" autocomplete="off" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">ID Persona</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="idPersona" value="<?php echo $_REQUEST["idPersona"] ?? $data["resultado_consulta"]["cPerID"]; ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">ID Trabajador</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="idTrabajador" value="<?php echo $_REQUEST["idTrabajador"] ?? $data["resultado_consulta"]["cTraID"]; ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">ID Usuario</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="idUsuario" value="<?php echo $_REQUEST["idUsuario"] ?? $data["resultado_consulta"]["cUserID"]; ?>" readonly>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Nombres</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="txtNombres" value="<?php echo $_REQUEST["txtNombres"] ?? $data["resultado_consulta"]["cPerNombre"]; ?>">
                            <?php if (isset($data["errores"]["nombres"])) : ?>
                                <div class="text-danger">
                                    <?php echo $data["errores"]["nombres"]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Apellidos</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="txtApellido" value="<?php echo $_REQUEST["txtApellido"] ?? $data["resultado_consulta"]["cPerApellidos"]; ?>">
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
                            <input type="date" class="form-control" name="fechaNacimiento" value="<?php echo $_REQUEST["fechaNacimiento"] ?? $data["resultado_consulta"]["cPerEdad"]; ?>">
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
                            <select class="form-control" name="cbTipoDoc" id="cbTipoDoc">
                                <option value="<?php echo $_REQUEST["cbTipoDoc"] ?? ''; ?>">--SELECCIONE UN TIPO DE DOCUMENTO</option>
                                <?php foreach ( $data["tipoDocumento"] as $tipoDoc ) :?>
                                    <option value="<?= $tipoDoc["iTipoDocID"]?>"><?php echo $tipoDoc["tTipoDocNombre"];?></option>
                                <?php endforeach; ?>
                            </select>

                            <script>
                                const comboBox = document.getElementById("cbTipoDoc");
                                console.log(comboBox);
                                var valorTipoDoc = '<?php echo $data["resultado_consulta"]["iTipoDocID"];?>';
                                for (var i = 0; i < comboBox.options.length; i++) {
                                    if (valorTipoDoc == comboBox.options[i].value) {
                                        comboBox.selectedIndex = i;
                                        comboBox.focus();
                                        break;
                                    }
                                }
                            </script>

                            <?php if (isset($data["errores"]["tipo_documento"])) : ?>
                                <div class="text-danger">
                                    <?php echo $data["errores"]["tipo_documento"]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Numero de documento</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="txtDNI" value="<?php echo $_REQUEST["txtDNI"] ?? $data["resultado_consulta"]["tPerNumDoc"]; ?>">
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
                            <input type="email" class="form-control" name="txtEmail" value="<?php echo $_REQUEST["txtEmail"] ?? $data["resultado_consulta"]["cPerCorreo"]; ?>">
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
                                const valorGenero = "<?php echo $data["resultado_consulta"]["cPerGenero"]?>";
                                for (var i = 0; i < selectGenero.options.length; ++i) {
                                    if ( valorGenero === selectGenero.options[i].value ){
                                        selectGenero.selectedIndex = i;
                                        selectGenero.focus();
                                        break;
                                    }
                                }
                            </script>

                            <?php if (isset($data["errores"]["genero"])) : ?>
                                <div class="text-danger">
                                    <?php echo $data["errores"]["genero"]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">País</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="txtPais" value="<?php echo $_REQUEST["txtPais"] ?? $data["resultado_consulta"]["cPerPais"]; ?>">
                            <?php if (isset($data["errores"]["pais"])) : ?>
                                <div class="text-danger">
                                    <?php echo $data["errores"]["pais"]; ?>
                                </div>
                            <?php endif; ?>
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

                            <script>
                                const selectCargo = document.getElementsByName("selectCargo")[0];
                                const cargo = "<?php echo $data["resultado_consulta"]["iCarID"];?>";
                                for ( var i = 0; i < selectCargo.options.length; ++i){
                                    if ( cargo == selectCargo.options[i].value ){
                                        selectCargo.selectedIndex = i;
                                        selectCargo.focus();
                                        break;
                                    }
                                }
                            </script>

                            <?php if (isset($data["errores"]["cargo"])) : ?>
                                <div class="text-danger">
                                    <?php echo $data["errores"]["cargo"]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Nombre de usuario</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" class="form-control" name="txtNUsuario" value="<?php echo $_REQUEST["txtNUsuario"] ?? $data["resultado_consulta"]["cUserNUsuario"]; ?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="botonCompletador">
                                        <span id="toggleAutoCompleteIcon" class="fa fa-eye"></span>
                                    </button>
                                    <script>
                                        var boton = document.getElementById("botonCompletador");
                                        console.log("Botón seleccionado:", boton);
                                        boton.click();
                                    </script>
                                </div>
                            </div>
                            <?php if (isset($data["errores"]["nombre_usuario"])) : ?>
                                <div class="text-danger">
                                    <?php echo $data["errores"]["nombre_usuario"]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Contraseña</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="password" class="form-control" name="txtContra" value="<?php echo $_REQUEST["txtContra"] ?? $data["resultado_consulta"]["cUserClave"];?>">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <span id="toggleIcon" class="fa fa-eye"></span>
                                    </button>
                                </div>
                            </div>
                            <?php if (isset($data["errores"]["contrasenia"])) : ?>
                                <div class="text-danger">
                                    <?php echo $data["errores"]["contrasenia"]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Sueldo</label>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="number" class="form-control" name="numberSueldo" value="<?php echo $_REQUEST["numberSueldo"] ?? $data["resultado_consulta"]["fTraSueldo"];?>">
                            </div>
                            <?php if (isset($data["errores"]["sueldo"])) : ?>
                                <div class="text-danger">
                                    <?php echo $data["errores"]["sueldo"]; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <input type="submit" value="Actualizar Trabajador" class="btn btn-block btn-success" name="btnEnviar">
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
        const nombreUsuarioInput = $('[name="txtNUsuario"]');
        const nombresInput = $('[name="txtNombres"]');
        const apellidoInput = $('[name="txtApellido"]');
        let isTextEmpty = true;

        toggleAutoCompleteButton.on('click', function() {
            isTextEmpty = !isTextEmpty;
            toggleAutoCompleteIcon.toggleClass('fa-user fa-user-slash');
            nombreUsuarioInput.attr('readonly', isTextEmpty);

            if (isTextEmpty) {
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
            } else {
                nombreUsuarioInput.removeAttr("placeholder").val("");
            }
        });
    });        

    $(document).ready(function(){
        const togglePasswordButton = $('#togglePassword');
        const toggleIcon = $('#toggleIcon');
        const passwordField = $('[name="txtContra"]');

        togglePasswordButton.on('click', function () {
            const isPasswordVisible = passwordField.attr('type') === 'text';
            passwordField.attr('type', isPasswordVisible ? 'password' : 'text');
            toggleIcon.toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>
