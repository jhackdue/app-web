function ucWords(string) {
  var arrayWords;
  var returnString = '';
  var len;
  arrayWords = string.split(' ');
  len = arrayWords.length;
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
  return string.substr(0, 1).toUpperCase() + string.substr(1, string.length).toLowerCase();
}

function mostrarCurso(id) {
  var grado = $('#grado').val();
  if (grado == '' || grado == undefined) {
    $(id).val('');
  } else {
    var mostrar = (grado == 'GRADO EN CIENCIAS MATEMÁTICAS') ? 'mat' : 'ing';
    var ocultar = (mostrar == 'mat') ? 'ing' : 'mat';
    $('option[class="' + mostrar + '"]').show();
    $('option[class="' + ocultar + '"]').hide();
  }
};

function perfil() {
  var data = { ajax: true };
  $.post('../../controlador/datosConectado.php', data, respuestaPerfil).fail(errorPerfil);
  return false;
};

function respuestaPerfil(datosDevueltos, status) {
  var datos = JSON.parse(datosDevueltos);

  var nombre = datos.nombre;
  var nombre = ucWords(nombre);

  document.title = 'Configuracion: ' + nombre;

  for (x in datos) {
    switch (x) {

      case 'password':
        continue;

      case 'grado':
      case 'curso':
        $('select option[value="' + datos[x] + '"]').attr('selected', true);
        break;

      case 'background':
        $('.jumbotron').css('background-image', 'url("../img/backgrounds/' + datos[x] + '")');
        $('.jumbotron').css('background-size', 'cover');
        $('.jumbotron').css('background-position', 'center');
        $('.jumbotron').css('background-repeat', 'no-repeat');
        break;

      case 'foto':
        $('#foto_perfil').attr('src', '../img/perfiles/' + datos[x]);
        break;

      case 'email':
        $('input[name=' + x + ']').val(datos[x]);
        break;

      default:
        $('input[name=' + x + ']').val(ucWords(datos[x]));
        break;
    }
  };
};

function errorPerfil(xhr, status, msg) {
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

function noVacio(str) {
  var x = (str.replace('\n', ' ')).replace('/\s\s+/', ' ');
  var x = x.trim();

  if (x) {
    return true;
  }else {
    return false;
  };
};

function cambiar(id) {
  var exito = false;
  var dato;
  var formData = new FormData();
  var x = document.getElementById(id);
  var campo = x.name;

  switch (campo) {
    case 'foto':
    case 'background':
      dato = x.files[0];

      if (dato.size > 2097152) {
        exito = false;
        alert('El tamaño es demasiado grande');
      } else {
        exito = true;
      };

      break;

    case 'email':
      exito = false;
      alert('Ponte en contacto con jhackdue@ucm.es');
      break;

    case 'nacimiento':
      var dato = x.value;
      if (noVacio(dato)) {
        var re = /^(19[5-9][0-9]|20[0-1][0-9])(-)(0[1-9]|1[0-2])\2([0-2][0-9]|3[0-1])$/;
        exito = ((re.test(dato)) ? true : false);
      } else {
        exito = false;
      };

      break;

    default:
      dato = x.value;
      exito = (noVacio(dato) ? true : false);
      break;

  };

  if (exito) {
    formData.append(campo, dato);

    $.ajax({
      url: '../../controlador/actualizar.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function (data, textStatus, jqXHR) {
        alert('cambiado');
        perfil();
      },

      error: function (jqXHR, textStatus, errorThrown) {
        console.log(errorThrown);
      },
    });
  };

  return false;
};

$(document).ready(function () {

  $('option[class="mat"], option[class="ing"]').hide();

  $('#datetimepicker1').datetimepicker({
    maxDate: moment().subtract(18, 'years'),
    minDate: moment().subtract(70, 'years'),
    locale: 'es',
    format: 'YYYY-MM-DD',
  });

  perfil();
});
