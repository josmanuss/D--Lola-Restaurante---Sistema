<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $data["titulo"]; ?></h3>
                </div>
                <div class="card-body">
                    <form action="index.php?c=DocenteController&a=actualizar" method="POST" autocomplete="off" enctype="multipart/form-data">
                        <input type="text" class="hidden" name="txtPersona" value="<?php echo $data["consulta"]["id_persona"]; ?>"><br>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Nombres</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtNombres" value="<?php echo isset($_REQUEST['txtNombres']) ? $_REQUEST['txtNombres'] : $data["consulta"]["nombres"]; ?>">
                                <?php if (isset($data["errores"]["nombres"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["nombres"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Apellidos:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtApellidos" value="<?php echo isset($_REQUEST['txtApellidos']) ? $_REQUEST['txtApellidos'] : $data["consulta"]["apellidos"]; ?>">
                                <?php if (isset($data["errores"]["apellidos"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["apellidos"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Correo:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtEmail" value="<?php echo isset($_REQUEST['txtEmail']) ? $_REQUEST['txtEmail'] : $data["consulta"]["correo"]; ?>">
                                <?php if (isset($data["errores"]["correo"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["correo"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">DNI:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtDNI" value="<?php echo isset($_REQUEST['txtDNI']) ? $_REQUEST['txtDNI'] : $data["consulta"]["dni"]; ?>">
                                <?php if (isset($data["errores"]["dni"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["dni"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Tipo Contrato:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="txtContrato" value="<?php echo isset($_REQUEST['txtContrato']) ? $_REQUEST['txtContrato'] : $data["consulta"]["tipoContrato"]; ?>">
                                <?php if (isset($data["errores"]["tipoContrato"])) : ?>
                                    <div class="text-danger">
                                        <?php echo $data["errores"]["tipoContrato"]; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Fecha Registro:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" readonly name="txtFechRegistro" value="<?php echo $data["consulta"]["fecha_registro"]; ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-9">
                                <a href="index.php?c=AlumnoController" class="btn btn-secondary me-2">CANCELAR REGISTRO</a>
                                <input type="submit" value="ACTUALIZAR DATOS" class="btn btn-success" name="btnEnviar">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
