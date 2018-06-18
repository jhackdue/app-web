var login = function () {
  var formLogin = $(this).serialize();
  $.post('../../controlador/administracion/iniciar_sesion.php', formLogin,
  respuestaLogin).fail(errorLogin);
  return false;
};

function respuestaLogin(datosDevueltos) {

  if (datosDevueltos.indexOf('falso_e') > -1) {
    alert('Este administrador no existe');
  }else if (datosDevueltos.indexOf('falso_c') > -1) {
    alert('Contraseña errónea');
  }else {
    location.assign('../administracion/administrador.php');
  }
};

function errorLogin() {
  var msgError = 'Oooops!! Ha ocurrido algo inesperado. Por favor inténtalo más tarde';
  alert(msgError);
};

$(document).ready(function () {
  $('#form').submit(login);

});
