function Volver() {
  location.assign('../index.php');
  return false;
}

function mostrarCurso(id) {
  var grado = $('#formulario_grado').val();
  if (grado == '' || grado == undefined) {
    $(id).val('');
  } else {
    var mostrar = (grado == 'GRADO EN CIENCIAS MATEMÁTICAS') ? 'mat' : 'ing';
    var ocultar = (mostrar == 'mat') ? 'ing' : 'mat';
    $('option[class="' + mostrar + '"]').show();
    $('option[class="' + ocultar + '"]').hide();
  }
};

function respuestaRegistro(datosDevueltos, status) {
  if (datosDevueltos == 'existe') {
    $('#mensaje_email').show();
  }else if (datosDevueltos == 'registrado') {
    location.assign('../principal/index.php');
  };
};

function errorRegistro(xhr, status, msg) {
  console.log(msg);;
};

function cambioHora() {
  var re = /^(19[5-9][0-9]|20[0-1][0-9])(-)(0[1-9]|1[0-2])\2([0-2][0-9]|3[0-1])$/;
  var isDate = re.test($('#formulario_nacimiento').val());
  if (isDate) {
    $('#formulario_nacimiento').removeClass('invalid').addClass('valid');
    var delBien = $('#formulario_nacimiento').parent().parent().children().last();
    delBien.removeClass('glyphicon-remove error mostrar_mal');
    delBien.addClass('glyphicon-ok mostrar_bien');
  } else {
    $('#formulario_nacimiento').removeClass('valid').addClass('invalid');
    var delBien = $('#formulario_nacimiento').parent().parent().children().last();
    delBien.removeClass('glyphicon-ok mostrar_bien').addClass('glyphicon-remove error');
  };
};

$(document).ready(function () {

  $('option[class="mat"], option[class="ing"]').hide();

  $('#datetimepicker1').datetimepicker({
    maxDate: moment().subtract(18, 'years'),
    locale: 'es',
    format: 'YYYY-MM-DD',
  }).on('dp.change', cambioHora());

  $('.glyphicon-eye-open').mouseover(function () {
    $('#formulario_password').attr('type', 'text');
  });

  $('.glyphicon-eye-open').mouseout(function () {
    $('#formulario_password').attr('type', 'password');
  });

  $('#formulario_nombre, #formulario_curso, ' +
  '#formulario_grado').on('input', function () {
    var isName = ($(this).val().replace('\n', ' ')).replace('/\s\s+/', ' ');
    var isName = isName.trim();

    if (isName) {
      $(this).removeClass('invalid').addClass('valid');
      var delMal = $(this).next().removeClass('glyphicon-remove error mostrar_mal');
      delMal.addClass('glyphicon-ok mostrar_bien');
    }	else {
      $(this).removeClass('valid').addClass('invalid');
      var delBien = $(this).next().removeClass('glyphicon-ok mostrar_bien');
      delBien.addClass('glyphicon-remove error');
    }
  });

  $('#formulario_email').on('input', function () {
    $('#mensaje_email').hide();
    var re = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9._-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    var isEmail = re.test($(this).val());
    if (isEmail) {
      $(this).removeClass('invalid').addClass('valid');
      var delMal = $(this).next().removeClass('glyphicon-remove error mostrar_mal');
      delMal.addClass('glyphicon-ok mostrar_bien');
    } else {
      $(this).removeClass('valid').addClass('invalid');
      var delBien = $(this).next().removeClass('glyphicon-ok mostrar_bien');
      delBien.addClass('glyphicon-remove error');
    };
  });

  $('#formulario_password').on('input', function () {
    var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
    var isPass = re.test($(this).val());
    if (isPass) {
      $(this).removeClass('invalid').addClass('valid');
      var delMal = $(this).parent().parent().children().last();
      delMal.removeClass('glyphicon-remove error mostrar_mal');
      delMal.addClass('glyphicon-ok mostrar_bien');
    } else {
      $(this).removeClass('valid').addClass('invalid');
      var delBien = $(this).parent().parent().children().last();
      delBien.removeClass('glyphicon-ok mostrar_bien').addClass('glyphicon-remove error');
    };
  });

  $('#formulario').submit(function () {
    var formData = $('#formulario').serializeArray();
    var errorFree = true;
    for (var input in formData) {
      var completa = formData[input].name;
      var $element = $('#formulario_' + completa);
      var valid = $element.hasClass('valid');

      if (completa == 'nacimiento' || completa == 'password') {
        var errorElement = $element.parent().parent().children().last();
      } else if (completa == 'tabla') {
        var errorElement = $element;
      } else {
        var errorElement = $element.next();
      };

      if (!valid) {
        errorElement.addClass('mostrar_mal');
        errorElement.removeClass(' error glyphicon-ok').addClass('glyphicon-remove');
        errorFree = false;
      } else {
        errorElement.removeClass('error mostrar_mal').addClass('mostrar_bien');
        errorElement.removeClass('glyphicon-remove').addClass('glyphicon-ok');
      };
    };

    if (!errorFree) {
      return false;
    } else {
      var formRegistro = $('#formulario').serialize();
      var enlace = '../../controlador/registrar.php';
      $.post(enlace, formRegistro, respuestaRegistro).fail(errorRegistro);
      return false;
    };
  });
});
