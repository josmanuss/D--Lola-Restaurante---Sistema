<div class="content-wrapper">
    <!-- Header Section -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <!-- Title -->
                    <h1 class="m-0"><?php echo $data["titulo"]; ?></h1>
                </div>
                <div class="col-sm-4">
                    <!-- Session Message -->
                    <?php if (isset($_SESSION["mensaje"])) : ?>
                        <div id="alert-msj" class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong><?php echo $_SESSION["mensaje"]; ?></strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <!-- Session Message Script -->
                        <script>
                            setTimeout(function() {
                                $('#alert-msj').fadeOut('fast');
                            }, 3000);
                        </script>
                        <?php unset($_SESSION["mensaje"]);
                    endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Card Section -->
                    <div class="card">
                        <div class="card-body">
                            <!-- Card Title -->
                            <h5 class="card-title">Resumen de Transacciones</h5>

                            <!-- Transaction Information -->
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Transaction Details -->
                                    <p class="card-text">Total de Transacciones: <strong>100</strong></p>
                                    <p class="card-text">Monto Total: <strong>$5000</strong></p>
                                    <p class="card-text">Transacciones Pendientes: <strong>5</strong></p>
                                    <p class="card-text">Última Transacción: <strong>18/02/2024</strong></p>
                                </div>
                                <div class="col-md-6">
                                    <!-- Transaction Chart -->
                                    <canvas id="transaccionesChart"></canvas>
                                </div>
                            </div>

                            <!-- Button to view details -->
                            <a href="#" class="btn btn-primary">Ver detalles</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<!-- Incluir librerías para el gráfico -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Datos de ejemplo para el gráfico de transacciones
    var ctx = document.getElementById('transaccionesChart').getContext('2d');
    var transaccionesChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
            datasets: [{
                label: 'Transacciones',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>


    <div class="modal fade" id="modalRegistroCategoria" tabindex="-1" role="dialog" aria-labelledby="modalRegistroCategoriaLabel" aria-hidden="true">    
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRegistroPlatoLabel">Formulario de registro de nueva categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" autocomplete="off" enctype="multipart/form-data">
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
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <input type="submit" value="Registrar Categoria" class="btn btn-block btn-success" name="btnEnviar">
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