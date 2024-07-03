function obtenerDatosProductos(){
    let arrayProductos = [];
    $.ajax({
        url : "index.php?c=VentaController&a=obtenerReporteTotalProductos",
        method : "POST",
        async : false,
        success : function(response){
            var respuesta = JSON.parse(response);
            if ( respuesta.success ){
                arrayProductos = respuesta.productos;
            }
            else{
                return null;
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            arrayProductos = null; 
        }

    });

    return arrayProductos;
}


function graficoProductosVendidos(){
    let grafico = document.getElementById("myBarChartPV").getContext('2d');
    let productos = obtenerDatosProductos();

    if (productos !== null && productos.length > 0) {
        let titulos = productos.map(function(item) {
            return item[0];
        });
        let cantidades = productos.map(function(item) {
            return item[1];
        });

        var chart = new Chart(grafico,{
            type : "pie",
            data : {
                labels : titulos, 
                datasets : [
                    {
                        label : "Productos más vendidos",
                        backgroundColor:  ["rgb(255,0,0)", "rgb(255,127,0)", "rgb(255,255,0)", "rgb(0,255,0)", "rgb(0,0,255)", "rgb(75,0,130)", "rgb(148,0,211)"],
                        data: cantidades
                    }
                ]
            }
        });
    } else {
        var chart = new Chart(grafico, {
            type: 'pie',
            data: {
                labels: ['Sin datos'], 
                datasets: [{
                    label: "Productos más vendidos",
                    backgroundColor: ["rgb(192,192,192)"],
                    data: [1]
                }]
            }
        });
    }
}

function graficoTotalVentas(){
    $.ajax({
        url: 'index.php?c=AdministradorController&a=reportesVentasMensuales', 
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                console.log(data.ventas);
                const ventasData = new Array(12).fill(0);
                data.ventas.forEach(venta => {
                    ventasData[venta.Mes - 1] = venta.CantidadVendida;
                });
                
                const ctx = $('#myBarChartVentas')[0].getContext('2d');
                const ventasChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                        datasets: [{
                            label: 'Cantidad de Ventas',
                            data: ventasData,
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: false,
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Meses'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Cantidad Vendida'
                                }
                            }
                        }
                    }
                });
            } else {
                console.error('Error al obtener los datos de ventas mensuales.');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
        }
    });
}


graficoProductosVendidos();
graficoTotalVentas();