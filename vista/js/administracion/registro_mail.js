var registro = function () {
  var formRegistro = $(this).serialize();

  $.post('../../controlador/administracion/registrar_mail.php',
          formRegistro, respuestaRegistro).fail(errorRegistro);
  return false;
};

function respuestaRegistro(datosDevueltos) {
  if (datosDevueltos.indexOf('accion_registrada') > -1) {
    alert('Ya hay datos para esa acción');

  }else {
    location.assign('../administracion/administrador.php');

    // alert(datosDevueltos);
  }
};

function errorRegistro() {
  var msgError = 'Oooops!! Ha ocurrido algo inesperado. Por favor inténtalo más tarde';
  alert(msgError);
};

$(document).ready(function () {

  // $('#datetimepicker1').datetimepicker({
  //   format: 'YYYY-MM-DD',
  //   locale: 'es',
  //   maxDate: '2000-12-31',
  // });

  $('#formu_registro').submit(registro);

  // var aparece = function () {
  //   alert('Escribe aquí tu nombre completo');
  // };

  // Cuando no esté selccionado un elemento input del formulario, si la cadena es
  // mayor que 0, poner un check
  // <i class="fa fa-check" aria-hidden="true"></i>
});
