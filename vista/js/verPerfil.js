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
  var tabla = $('input[type="hidden"]').val();
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

function GetURLParameter(sParam) {
  var sPageURL = window.location.search.substring(1);
  var sURLVariables = sPageURL.split('&');
  for (var i = 0; i < sURLVariables.length; i++)
  {
    var sParameterName = sURLVariables[i].split('=');
    if (sParameterName[0] == sParam)
    {
      return sParameterName[1];
    }
  }
};

function buscar() {
  var url = '../../controlador/verPerfil.php';
  var perfil = GetURLParameter('perfil');
  var usuario = GetURLParameter('usuario');
  var data = 'perfil=' + perfil + '&usuario=' + usuario;

  $.post(url, data, respuestaBuscar).fail(errorBuscar);
};

function respuestaBuscar(datosDevueltos, status) {
  var datos = JSON.parse(datosDevueltos);

  for (x in datos) {
    switch (x) {

      case 'password':
        continue;

      case 'background':
        var url = 'url("../img/backgrounds/' + datos[x] + '")';
        $('#background_perfil').css('background-image', url);
        $('#background_perfil').css('background-size', 'cover');
        $('#background_perfil').css('background-position', 'left');
        $('#background_perfil').css('background-repeat', 'no-repeat');
        break;

      case 'foto':
        $('#foto_perfil').attr('src', '../img/perfiles/' + datos[x]);
        break;

      default:
        $('#contenido_info dd[id="' + x + '"]').html(datos[x]);
        break;
    }
  }
};

function verSubidas(m, p) {
  var perfil = GetURLParameter('perfil');
  var usuario = GetURLParameter('usuario');
  var m = (m == '') ? String(15) : String(m);
  var p = (p == '') ? String(1) : String(p);
  var url = '../../controlador/' +
  'verActividad.php?mostrar=' + m + '&pagina=' + p;
  var data = 'perfil=' + perfil + '&usuario=' + usuario + '&accion=subir';
  $.post(url, data, function (datosDevueltos) {
    if (screen.width < 768) {
      $('#contenido_publicaciones #subidos').html(datosDevueltos);
    } else {
      $('#contenido_publicaciones #subidos2').html(datosDevueltos);
    };
  }).fail(function (xhr, status, msg) {
    console.log(msg);
  });
};

function errorBuscar(xhr, status, error) {
  console.log(error);
};

function cargar() {
  buscar();
  verSubidas(15, 1);
}

$(document).ready(function () {
  $('#form').submit(login);

  window.onload = cargar();
});
