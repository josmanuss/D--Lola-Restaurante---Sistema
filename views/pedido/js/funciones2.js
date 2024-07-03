$(document).ready(function(){
    let arrayProductos, arrayProductoInput = [];

    function obtenerProductos() {
        let arrayProductoEncontrado = [];
        $.ajax({
            url: "index.php?c=PlatoController&a=todos",
            method: 'POST',
            async: false, 
            success: function(response){
                var respuesta = JSON.parse(response);
                if (respuesta.success) {
                    arrayProductoEncontrado = respuesta.platos;
                } else {
                    console.error("Error al obtener productos");
                    arrayProductoEncontrado = []; 
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                arrayProductoEncontrado = []; 
            }
        });
        return arrayProductoEncontrado; 
    }

    arrayProductos = obtenerProductos();

    if (arrayProductos.length > 0) {
        for (let i = 0; i < arrayProductos.length; i++) {
            arrayProductoInput.push(arrayProductos[i]["cPlaNombre"]+'  -  '+arrayProductos[i]["cPlaPrecio"]);
        }
    } else {
        console.error("El primer array no tiene elementos suficientes.");
    }
    
    var input = $('#buscarPlato');
    var resultsContainer = $('#autocomplete-results');

    input.on('input', function() {
        var inputValue = input.val().toLowerCase();
        if (inputValue === '') {
            resultsContainer.hide();
            return;
        }
        
        var results = arrayProductoInput.filter(function(palabra) {
            return palabra.toLowerCase().includes(inputValue);
        });
        mostrarResultados(results);
    });

    function mostrarResultados(results) {
        resultsContainer.empty();

        $.each(results, function(index, result) {
            var resultItem = $('<div class="result-item"></div>').text(result);
            resultItem.on('click', function() {
                input.val(result);
                resultsContainer.hide();
            });
            resultsContainer.append(resultItem);
        });
        resultsContainer.show();
    }

    $(document).on('click', function(event) {
        if (!resultsContainer.is(event.target) && !input.is(event.target)) {
            resultsContainer.hide();
        }
    });

});