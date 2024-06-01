<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <h1 class="m-0 text-primary"><?php echo $data["titulo"]; ?></h1>
                </div>
                <div class="col-sm-4"></div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped">
                                <tbody>
                                    <tr>
                                        <td class="font-weight-bold">Nombre</td>
                                        <td><?php echo $_SESSION["trabajador"]["cPerNombre"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Apellidos</td>
                                        <td><?php echo $_SESSION["trabajador"]["cPerApellidos"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Fecha de Nacimiento</td>
                                        <td><?php echo $_SESSION["trabajador"]["cPerEdad"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Genero</td>
                                        <td><?php echo $_SESSION["trabajador"]["cPerGenero"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Pais:</td>
                                        <td><?php echo $_SESSION["trabajador"]["cPerPais"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Cargo</td>
                                        <td><?php echo $_SESSION["trabajador"]["iCarID"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Sueldo</td>
                                        <td><?php echo $_SESSION["trabajador"]["fTraSueldo"]?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
