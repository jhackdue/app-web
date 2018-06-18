function setCookie(cName, value, exdays, path) {
  var exdate = new Date();
  exdate.setDate(exdate.getDate() + exdays);
  var cValue = escape(value) + ((exdays == null) ? '' : '; expires=' + exdate.toUTCString());
  var domain = '; domain=chusky.fdi.ucm.es';
  var host = '/~jhack/tfg/';
  cValue = cValue + ((path == null) ? (domain + '; path=' + host) : '; path=' + path);
  document.cookie = cName + '=' + cValue;
};

function Volver() {
  setCookie('tabla', '', -1);
  location.assign('../index.php');
  return false;
}

var login = function () {
  var formLogin = $(this).serialize();
  $.post('../../controlador/recuperar.php', formLogin, respuestaLogin).fail(errorLogin);
  return false;
};

function respuestaLogin(datosDevueltos, status) {

  if (datosDevueltos == 'no_existe') {
    $('.alert-warning').hide();
    $('.alert-info').hide();
    $('.alert-danger').show();
  }else if (datosDevueltos == 'error_enviar') {
    $('.formulario').hide();
    $('.alert-info').hide();
    $('.alert-danger').hide();
    $('.alert-warning').show();
  }else if (datosDevueltos == 'enviado') {
    $('.formulario').hide();
    $('.alert-warning').hide();
    $('.alert-danger').hide();
    $('.alert-info').show();
  } else if (datosDevueltos == 'conectado') {
    alert('¡Ya estás conectado!');
  };
};

function errorLogin(xhr, status, msg) {
  console.log(msg);
};

$(document).ready(function () {
  $('.formulario').submit(login);
});
