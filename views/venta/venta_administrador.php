<style>
    .btn {
        margin-right: 10px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table th, .table td {
        padding: 8px; 
        border: 1px solid #ddd; 
    }

    .table th {
        background-color: #f2f2f2; 
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9; 
    }

    .btn-danger, .btn-primary {
        padding: 8px 12px; 
        border: none; 
        border-radius: 5px; 
        cursor: pointer; 
    }

    .btn-danger:hover, .btn-primary:hover {
        opacity: 0.8; 
    }

    .btn-danger i, .btn-primary i {
        margin-right: 5px; 
    }

    .float-right {
        float: right; 
    }

    .mr-1 {
        margin-right: 10px;
    }

    .mr-4 {
        margin-right: 20px; 
    }
</style>


<div class="content-wrapper" id="contenidoAdmin">
    <div class ="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-5">
                    <h1 class="m-0"><?php echo $data["titulo"];?></h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <label for="fecha_inicial">Fecha Inicial:</label>
                    <input type="date" id="fecha_inicial" name="fecha_inicial" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="fecha_final">Fecha Final:</label>
                    <input type="date" id="fecha_final" name="fecha_final" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <input type="submit" id="busquedafechas" name="busquedafechas" value = "Buscar entre fechas" class="form-control btn btn-primary">
                </div>
                <div class="col-md-3">
                    <label for="fecha_final">&nbsp;</label>
                    <input type="submit" id="mostrartodas" name="mostrartodas" value = "Mostrar todas" class="form-control btn btn-primary">
                </div>
            </div>
        </div>
    </div>
    
    <div class="content mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-1"></i>
                            Reporte de Ventas
                        </div>
                        <div class="card-body"><canvas id="myBarChartVentas" width="100%" height="40"></canvas></div>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function graficoVentas(){
    let grafico = document.getElementById("myBarChartVentas").getContext('2d');
    let ventas = obtenerDatosVentas();

    if (ventas !== null && ventas.length > 0) {
        let titulo = ventas.map(function(item) {
            return item[0];
        });
        let cantidad = ventas.map(function(item) {
            return item[1];
        });

        var chart = new Chart(grafico,{
            type : "bar",
            data : {
                labels : titulo, 
                datasets : [
                    {
                        label : "",
                        backgroundColor:  ["rgb(255,0,0)", "rgb(255,127,0)", "rgb(255,255,0)", "rgb(0,255,0)", "rgb(0,0,255)", "rgb(75,0,130)", "rgb(148,0,211)"],
                        data: cantidad
                    }
                ]
            }
        });
    } else {
        console.error("No se han obtenido datos de trabajadores o los datos son inválidos.");
    }
}

function obtenerDatosVentas(){
    let arrayVentas = [];
    $.ajax({
        url : "index.php?c=VentaController&a=obtenerDatosVentas",
        method : "POST",
        async : false,
        success : function(response){
            var respuesta = JSON.parse(response);
            if ( respuesta.success ){
                arrayVentas = respuesta.productos;
            }
            else{
                return null;
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            arrayVentas = null; 
        }

    });

    return arrayVentas;
}
</script>
