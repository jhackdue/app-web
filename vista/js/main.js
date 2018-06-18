function esNull(v) {
  return v == null;
};

function ucWords(string) {
  var returnString = '';
  var arrayWords = string.split(' ');
  var len = arrayWords.length;
  for (i = 0; i < len; i++) {
    if (i != (len - 1)) {
      returnString = returnString + ucFirst(arrayWords[i]) + ' ';
    } else {
      returnString = returnString + ucFirst(arrayWords[i]);
    };
  };

  return returnString;
};

function ucFirst(string) {
  return string.substr(0, 1).toUpperCase() +
  string.substr(1, string.length).toLowerCase();
};

function verPerfil(perfil, id) {
  location.assign('verPerfil.php?perfil=' + perfil + '&usuario=' + id);
};

function recibidos() {
  var data = { ajax: true };
  $.post('../../controlador/recibidos.php', data, function (datosDevueltos) {
    if (isJson(datosDevueltos)) {
      var datos = JSON.parse(datosDevueltos);
      if (screen.width > 768) {
        $('#msgRecibidos2').html(datos.mensajes);
      } else {
        $('#msgRecibidos').html(datos.mensajes);
      };

      return true;
    } else {
      if (screen.width > 768) {
        $('#msgRecibidos2').html('0');
      } else {
        $('#msgRecibidos').html('0');
      };

      return false;
    };

  }).fail(function (xhr, status, error) {
    console.log(error);
  });

  return false;
}

function perfil() {
  var data = { ajax: true };
  $.post('../../controlador/datosConectado.php', data, respuestaPerfil).fail(errorPerfil);
};

function respuestaPerfil(datosDevueltos, status) {
  var datos = JSON.parse(datosDevueltos);

  var nombre = datos.nombre;
  var nombre = ucWords(nombre);

  document.title = 'App-web: ' + nombre;
  $('#nombre_sesion').html('<b>' + nombre + '</b>');

  for (x in datos) {
    switch (x) {

      case 'password':
        continue;

      case 'grado':
      case 'curso':
        if (datos[x] !== null) {
          $('select option[value="' + datos[x] + '"]').attr('selected', true);
        };

        break;

      case 'background':
        var url = 'url("../img/backgrounds/' + datos[x] + '")';
        $('#background_perfil').css('background-image', url);
        $('#background_perfil').css('background-size', 'cover');
        $('#background_perfil').css('background-position', 'left');
        $('#background_perfil').css('background-repeat', 'no-repeat');
        break;

      case 'foto':
        $('#foto_perfil').attr('src', '../img/perfiles/' + datos[x]);
        $('.foto_login').attr('src', '../img/perfiles/' + datos[x]);

        break;

      default:
        break;
    }
  };

  if (screen.width > 768) {
    verAsignaturas('.buscar');
    verContactos('.contactos');
    mensajesRecibidos('.recibidos');
    documentosPublicados(15, 1);
  } else {
    verAsignaturas('.buscar768');
    verContactos('.contactos768');
    mensajesRecibidos('.recibidos768');
    documentosPublicados(15, 1);
  };
};

function errorPerfil(xhr, status, error) {
  console.log(error);
};

function isJson(str) {
  try {
    JSON.parse(str);
  } catch (e) {
    return false;
  };

  return true;
};

function verAsignaturas(clase) {
  var url = '../../controlador/mostrar_busqueda.php';
  var data = $(clase).serialize() + '&tabla=asignaturas';
  $.post(url, data, respuestaAsignaturas).fail(errorAsignaturas);
  return false;
};

function respuestaAsignaturas(datosDevueltos, status) {
  if (screen.width > 768) {
    $('#pieBuscar').html(datosDevueltos);
  } else {
    $('#pieBuscar768').html(datosDevueltos);
  };
};

function errorAsignaturas(xhr, status, error) {
  console.log(error);
};

function verContactos(clase) {
  var url = '../../controlador/mostrar_busqueda.php';
  var data = $(clase).serialize();
  $.post(url, data, function (datosDevueltos, status) {
    if (screen.width > 768) {
      $('#pieContactos').html(datosDevueltos);
    } else {
      $('#pieContactos768').html(datosDevueltos);
    };
  }).fail(function (xhr, status, error) {
    console.log(error);
  });

  return false;

};

function mensajesRecibidos(clase) {
  var url = '../../controlador/mostrar_recibidos.php';
  var data = $(clase).serialize();
  $.post(url, data, function (datosDevueltos, status) {
    if (screen.width > 768) {
      $('#pieRecibidos').html(datosDevueltos);
    } else {
      $('#pieRecibidos768').html(datosDevueltos);
    };
  }).fail(function (xhr, status, error) {
    console.log(error);
  });
}

function documentosPublicados(m, p) {
  var m = (m == '') ? 15 : m;
  var p = (p == '') ? 1 : p;
  var url = '../../controlador/' +
  'mostrar_documentos.php?mostrar=' + String(m) + '&pagina=' + String(p);
  if (screen.width > 768) {
    var b = $('input[id="busqueda"]').val();
    var g = $('select[id="grado"]').val();
  } else {
    var b = $('input[id="busqueda768"]').val();
    var g = $('select[id="grado768"]').val();
  };

  var data = { tabla: 'gestionFicheros',
              busqueda: b,
              grado: g, };

  $.post(url, data, respuestaDocumentos).fail(errorDocumentos);

  return true;
};

function respuestaDocumentos(datosDevueltos, status) {
  if (screen.width > 768) {
    $('#pieDocumentos').html(datosDevueltos);
  } else {
    $('#pieDocumentos768').html(datosDevueltos);
  };
};

function errorDocumentos(xhr, status, error) {
  console.log(error);
};

function pintar(id, valoracion) {
  var si = Math.floor(valoracion / 2);
  var no = (valoracion % 2);
  if (no == 0) {
    for (var i = 1; i <= si; i++) {
      $(id + ' span[id="star_' + i + '"]').addClass('total_naranja');
    };

    for (var i = si + 1; i <= 5; i++) {
      $(id + ' span[id="star_' + i + '"]').removeClass('total_naranja');
      $(id + ' span[id="star_' + i + '"]').removeClass('medio_naranaja');
    }
  } else {
    var ultima = (no + si);
    for (var i = 1; i <= si; i++) {
      $(id + ' span[id="star_' + i + '"]').addClass('total_naranja');
    };

    $(id + ' span[id="star_' + ultima + '"]').addClass('medio_naranja');
    for (var i = ultima + 1; i <= 5; i++) {
      $(id + ' span[id="star_' + i + '"]').removeClass('total_naranja');
      $(id + ' span[id="star_' + i + '"]').removeClass('medio_naranaja');
    }
  }
};

function valorar(id, elemento) {
  if (elemento == 'fichero') {
    var valoracion = $('#valorar_' + id).val();
    var enviar = { accion: 'valorar',
                  tabla: 'gestionFicheros',
                  fila: id,
                  valor: valoracion,
                  valorado: elemento,
                };
    $.post('../../controlador/actualizar_fichero.php', enviar, function (d, status, msg) {
      if (d == 'valorado') {
        $('#valorar_' + id).hide();
        $('#formulario_' + id + ' textarea').attr('cols', '40');
      }else if (d == 'no_hay_valor') {
        $('#valorar_' + id).show();
      }else if (d == 'error_valorado') {
        console.log('error_valorado');
        console.log(status);
        console.log(msg);
        $('#valorar_' + id).show();
      }else if (isJson(d)) {
        var datos = JSON.parse(d);
        pintar('#stars-' + id, datos.valoracion);
        $('#valorar_' + id).hide();
        $('#formulario_' + id + ' textarea').attr('cols', '40');

      };
    }).fail(function (xhr, status, error) {
      console.log(error);
    });

  }else if (elemento == 'estudiantes' || elemento == 'profesores') {

    var valoracion = $('#contactos_' + elemento + ' #valorarUsuario_' + id).val();
    var enviar = { accion: 'valorar',
                  tabla: 'gestionFicheros',
                  tablaValorado: elemento,
                  fila: id,
                  valor: valoracion,
                  valorado: 'usuario',
                };
    $.post('../../controlador/actualizar_fichero.php', enviar, function (d, status, msg) {
      if (d == 'valorado') {
        $('#contactos_' + elemento + ' #valorarUsuario_' + id).hide();
      }else if (d == 'no_hay_valor') {
        $('#contactos_' + elemento + ' #valorarUsuario_' + id).show();
      }else if (d == 'error_valorado') {
        console.log('error_valoradoUsuario');
        console.log(status);
        console.log(msg);
        $('#contactos_' + elemento + ' #valorarUsuario_' + id).show();
      }else if (isJson(d)) {
        var datos = JSON.parse(d);
        $('#contactos_' + elemento + ' #valorUsuario_' + id).html(datos.valoracion);
        $('#contactos_' + elemento + ' #valorarUsuario_' + id).hide();
        documentosPublicados('', 1);
      };
    }).fail(function (xhr, status, error) {
      console.log(error);
    });

  }

  return false;
}

function firstOnload() {
  perfil();
  setInterval(recibidos, 10000);
};

$(document).ready(function () {
  window.onload = firstOnload();

  $('#actualizarCorreo').click(function () {
    recibidos();
    mensajesRecibidos('.recibidos');
  });

  $('#actualizarDocumentos').click(function () {
    documentosPublicados(15, 1);
  });

  $('#actualizarCorreo768').click(function () {
    recibidos();
    mensajesRecibidos('.recibidos768');
  });

  $('#actualizarDocumentos768').click(function () {
    documentosPublicados(15, 1);
  });
});
