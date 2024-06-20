$(document).ready(function () {
  $("#total").hide();

  function mostrarFilas(record_id) {
      $.ajax({
          url: "index.php?c=PedidoController&a=verDetallePedido",
          method: "POST",
          data: {
              record_id: record_id,
          },
          async: true,
          success: function (response) {
              var respuesta = JSON.parse(response);
              if (respuesta.success) {
                  var detalleV = respuesta.detalle;
                  var tbody = $("#tbl-DetallePlatos tbody");
                  var totalSum = 0;
                  $.each(detalleV, function (index, detalle) {
                      var fila =
                          "<tr>" +
                          "<td>" +
                          detalle[1] +
                          "</td>" +
                          "<td>" +
                          detalle[2] +
                          "</td>" +
                          "<td>" +
                          detalle[3] +
                          "</td>" +
                          "<td>" +
                          detalle[4] +
                          "</td>" +
                          "<td>" +
                          detalle[5] +
                          "</td>" +
                          "</tr>";
                      tbody.append(fila);
                      totalSum += parseFloat(detalle[5]) * parseFloat(detalle[4]);
                  });

                  var filaTotal =
                      "<tr>" +
                      '<td colspan="4" style="text-align: center;">SUB-TOTAL:</td>' +
                      '<td id="sub-total">S/.' +
                      "</td>" +
                      "</tr>";
                  tbody.append(filaTotal);
                  $("#op-gravadas").append((totalSum - totalSum * 0.18).toFixed(2));
                  $("#igv").append((totalSum * 0.18).toFixed(2));
                  $("#sub-total, #sub-total-pagar").append(totalSum.toFixed(2));
                  $("#vuelto").append(0);
                  
              } 
              else {
                  alert("No existe ese detalle de venta según el id a buscar");
              }
          },
          error: function (xhr, status, error) {
              alert("Error en la solicitud AJAX: " + error);
          },
      });
  }

  function pagarVenta(valorPagar) {
      $.ajax({
          url: "index.php?c=PedidoController&a=metodoPagarPedido",
          method: "POST",
          data: {
              id_pedido: $("#idVenta").text(),
              monto_pagado: valorPagar,
          },
          async: true,
          success: function (response) {
              var respuesta = JSON.parse(response);
              if (respuesta.success) {
                  Swal.fire({
                      icon: "success",
                      title: "Éxito",
                      text: respuesta.mensaje,
                      showConfirmButton: false,
                      timer: 2500,
                  }).then(() => {
                      window.location.href = "index.php?c=PedidoController";
                  });
              } else {
                  Swal.fire({
                      icon: "error",
                      title: "ERROR",
                      text: respuesta.mensaje,
                  });
              }
          },
          error: function (xhr, status, error) {
              console.error(xhr.responseText);
          },
      });
  }

  var div = $("<div></div>");
  div.attr("id", "idVenta");
  div.text(record_id);
  div.css({
      display: "none",
  });
  $("body").append(div);
  console.log(div.val());
  mostrarFilas(record_id);

  $(document).on(
      "input keyup",
      "#input-number, #pagosContainer input",
      function () {
          var totalEfectivo = 0;

          $("#pagosContainer input").each(function () {
              var valor = $(this)
                  .val()
                  .replace(/[^0-9.,]/g, "");
              if (!isNaN(valor) && valor !== "") {
                  totalEfectivo += parseFloat(valor);
              }
          });

          var totalTexto = $("#total").text();
          var separarTotal = totalTexto.match(/\d+(\.\d+)?/);
          var totalNumerico = parseFloat(separarTotal[0]);
          $("#total-final").text(totalEfectivo.toFixed(2));

          if (totalEfectivo >= totalNumerico) {
              var vuelto = totalEfectivo - totalNumerico;
              if (vuelto === 0) {
                  $("#vuelto").attr("class", "text text-danger");
                  $("#vuelto").text("VUELTO RECIBIDO: S/." + vuelto.toFixed(2));
              } else {
                  $("#vuelto").removeAttr("class");
                  $("#vuelto").text("VUELTO RECIBIDO: S/." + vuelto.toFixed(2));
              }
          } else {
              $("#vuelto").removeAttr("class");
              $("#vuelto").text("VUELTO RECIBIDO: S/.0");
          }
      }
  );

  $(document).on("change", "#dinero-exacto", function () {
      var checkeado = $(this).is(":checked");
      var totalTexto = $("#total").text();
      var separarTotal = totalTexto.match(/\d+(\.\d+)?/);
      var totalNumerico = parseFloat(separarTotal[0]);
      if (checkeado) {
          $("#input-number").val(totalNumerico);
      }
  });

  $("#pagosContainer").on("click", ".duplicate-btn", function (event) {
      if (!$("#dinero-exacto").is(":checked")) {
          const newPago = $(this).closest(".detallePagos").clone();
          newPago.find("input").val("");
          newPago.find(".dinero-exacto").parent().hide();
          newPago
              .find("button")
              .removeClass("duplicate-btn btn-primary")
              .html(
                  "<div>" +
                  '<button type="button" class="btn btn-sm btn-danger remove-btn">' +
                  '<i class="fas fa-minus"></i>' +
                  "</button>" +
                  "</div>"
              );
          $("#pagosContainer").append(newPago);
      } else {
          event.preventDefault();
          Swal.fire({
              icon: "warning",
              title: "ALERTA",
              text: "No necesitas agregar otro metodo si tienes el pago completo",
          });
      }
  });

  $("#pagosContainer").on("click", ".remove-btn", function () {
      $(this).closest(".detallePagos").remove();
      $("#input-number").trigger("input");
  });

  $("#botonPagar").on("click",function(event){


      // if ( cantidad < subtotal ){
      //   console.log(subtotal);
      //   console.log(cantidad);
      // }
  });

  function obtenerDetallesPlatos() {
      var detallesPlatos = [];
      $("#tbl-DetallePlatos tbody tr").not(":last").each(function () {
          var detalle = {};
          detalle.columna1 = $(this).find("td:eq(0)").text(); // Obtener texto de la primera columna
          detalle.columna4 = $(this).find("td:eq(3)").text(); // Obtener texto de la cuarta columna
          detallesPlatos.push(detalle);
      });

      return detallesPlatos;
  }

  setTimeout(function(){
    var subTotalText = $("#sub-total").text().match(/\d+(\.\d+)?/)[0];
    var subTotal = parseFloat(subTotalText);
    console.log(subTotal);
}, 2000);


});
