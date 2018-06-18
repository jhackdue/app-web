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

if (getCookie('acepta_cookies') != '1') {
  document.getElementById('barraaceptacion').style.display = 'block';
};

function PonerCookie() {
  setCookie('acepta_cookies', '1', 30);
  document.getElementById('barraaceptacion').style.display = 'none';
};

function Registro() {
  PonerCookie();
  location.assign('../vista/registro/registro.php');
  return false;
};

function Olvido() {
  PonerCookie();
  var tabla = $('input[type="hidden"]').val();
  setCookie('tabla', tabla, 1);
  location.assign('../vista/recuperar/recuperar.php');
  return false;
};

var contacto = function () {
  alert('Puedes contactar con jhackdue@ucm.es\n ');
};

var login = function () {
  var formLogin = $(this).serialize();
  $.post('../controlador/iniciar_sesion.php', formLogin, respuestaLogin).fail(errorLogin);
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
    location.assign('../vista/principal/index.php');
  };
};

function errorLogin(xhr, status, msg) {
  console.log(msg);
};

function isJson(str) {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  };

  return true;
};

function ir(h) {
  var enlace = document.getElementById(h);
  enlace.scrollIntoView();
};

$(document).ready(function () {

  $('#form').submit(login);

  $('#contacto').click(contacto);

  if (getCookie('tabla') != undefined) {
    setCookie('tabla', '', -1);
  };

  if (getCookie('email') != undefined) {
    setCookie('email', '', -1);
  };

});
