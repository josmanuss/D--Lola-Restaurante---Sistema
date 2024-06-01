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

function obtenerDatosTrabajadores(){
    let arrayTrabajadores = [];
    $.ajax({
        url : "index.php?c=TrabajadorController&a=mostrarCantidadTrabajadorCargo",
        method : "POST",
        async : false,
        success : function(response){
            var respuesta = JSON.parse(response);
            if ( respuesta.success ){
                arrayTrabajadores = respuesta.trabajadores;
            }
            else{
                return null;
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            arrayTrabajadores = null; 
        }

    });

    return arrayTrabajadores;
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
            type : "bar",
            data : {
                labels : titulos, 
                datasets : [
                    {
                        label : "Productos mas vendidos",
                        backgroundColor:  ["rgb(255,0,0)", "rgb(255,127,0)", "rgb(255,255,0)", "rgb(0,255,0)", "rgb(0,0,255)", "rgb(75,0,130)", "rgb(148,0,211)"],
                        data: cantidades
                    }
                ]
            }
        });
    } else {
        console.error("No se han obtenido datos de productos o los datos son inválidos.");
    }
}

function graficoTrabajadores(){
    let grafico = document.getElementById("myBarChartTrabajador").getContext('2d');
    let trabajadores = obtenerDatosTrabajadores();
    if (trabajadores !== null && trabajadores.length > 0) {
        let titulo = trabajadores.map(function(item) {
            return item[0];
        });
        let cantidad = trabajadores.map(function(item) {
            return item[1];
        });

        var chart = new Chart(grafico,{
            type : "pie",
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




graficoProductosVendidos();
graficoTrabajadores();