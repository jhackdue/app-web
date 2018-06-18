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

function setCookie(cName, value, exdays, path) {
  var exdate = new Date();
  exdate.setDate(exdate.getDate() + exdays);
  var cValue = escape(value) + ((exdays == null) ? '' : '; expires=' + exdate.toUTCString());

  var domain = '; domain=chusky.fdi.ucm.es';
  var host = '/~jhack/tfg/';
  cValue = cValue + ((path == null) ? (domain + '; path=' + host) : '; path=' + path);
  document.cookie = cName + '=' + cValue;
}

function Registro() {
  location.assign('../registro/registro.php');
  return false;
};

function Olvido() {
  var tabla = $('input[name="tabla"]').val();
  setCookie('tabla', tabla, 1);
  location.assign('../recuperar/recuperar.php');
  return false;
};

var login = function () {
  var formLogin = $(this).serialize();
  $.post('../../controlador/iniciar_sesion.php', formLogin, respuestaLogin).fail(errorLogin);
  return false;
};

function respuestaLogin(datosDevueltos, status) {
  if (datosDevueltos == 'falso_e') {
    alert('Ooooops! Este usuario no está registrado en la App-web');
  }else if (datosDevueltos == 'falso_c') {
    alert('Contraseña Errónea');
  }else if (datosDevueltos == 'cierto') {
    var tabla = $('input[name="tabla"]').val();
    setCookie('tabla', tabla, 1);
    location.assign('../principal/index.php');
  };
};

function verPerfil(perfil, id) {
  location.assign('verPerfil.php?perfil=' + perfil + '&usuario=' + id);
};

function errorLogin(xhr, status, msg) {
  console.log(msg);
};

function verPerfil(perfil, id) {
  location.assign('verPerfil.php?perfil=' + perfil + '&usuario=' + id);
};

function buscar(m, p) {
  var m = (m == '') ? String(15) : String(m);
  var p = (p == '') ? String(1) : String(p);
  var url = '../../controlador/' +
  'buscador_inicio.php?mostrar=' + m + '&pagina=' + p;
  if (screen.width > 992) {
    var data = $('#formulario_controladoresL .form-control').serialize();
  } else {
    var data = $('#formulario_controladoresR .form-control').serialize();
  };

  var perfil = $('input[name="get"]').val();
  var busqueda = $('input[id="busqueda"]').val();
  var data = data + '&perfil=' + perfil + '&busqueda=' + busqueda;
  $.post(url, data, respuestaBuscar).fail(errorBuscar);
  return true;
};

function respuestaBuscar(datosDevueltos, status) {
  $('#pieBusqueda').html(datosDevueltos);
};

function errorBuscar(xhr, status, error) {
  console.log(error);
};

$(document).ready(function () {
  $('#form').submit(login);
  window.onload = buscar('', 1);
});
