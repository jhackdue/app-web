var login = function () {
  var pass = $('#password').val();
  var rep = $('#repetir').val();
  if (pass != rep) {
    $('.alert-info').hide();
    $('.alert-danger').show();
  } else {
    var formLogin = $(this).serialize();
    var enlace = '../../controlador/modificar.php';
    $.post(enlace, formLogin, respuestaLogin).fail(errorLogin);
  };

  return false;
};

function respuestaLogin(datosDevueltos, status) {
  if (datosDevueltos.indexOf('error') > -1) {
    $('.alert-info').hide();
    $('.alert-danger').hide();
    $('.alert-warning').show();
  }else if (datosDevueltos == 'estudiante_modificado') {
    location.assign('../principal/index.php');
  }else if (datosDevueltos == 'profesor_modificado') {
    location.assign('../profesores/principal.php');
  };
};

function errorLogin(datosDevueltos) {
  $('.formulario').hide();
  $('.alert-info').hide();
  $('.alert-danger').hide();
  $('.alert-warning').show();
};

function getCookie(cName) {
  var cValue = document.cookie;
  var cStart = cValue.indexOf(' ' + cName + '=');
  if (cStart == -1) {
    cStart = cValue.indexOf(cName + '=');
  };

  if (cStart == -1) {
    cValue = null;
  } else {
    cStart = cValue.indexOf('=', cStart) + 1;
    var cEnd = cValue.indexOf(';', cStart);
    if (cEnd == -1) {
      cEnd = cValue.length;
    };

    cValue = unescape(cValue.substring(cStart, cEnd));
  };

  return cValue;
}

$(document).ready(function () {
  $('.form-control').on({
    focus: function () {
      $(this).attr('type', 'text');
      $('.alert-warning').hide();
      $('.alert-danger').hide();
      $('.alert-info').show();
    },

    blur: function () {
      $(this).attr('type', 'password');
      $('.alert-info').hide();
    },
  });

  $('.formulario').submit(login);

});
