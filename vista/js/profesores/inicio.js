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
};

function setCookie(cName, value, exdays, path) {
  var exdate = new Date();
  exdate.setDate(exdate.getDate() + exdays);
  var cValue = escape(value) + ((exdays == null) ? '' : '; expires=' + exdate.toUTCString());
  var domain = '; domain=chusky.fdi.ucm.es';
  var host = '/~jhack/tfg/';
  cValue = cValue + ((path == null) ? (domain + '; path=' + host) : '; path=' + path);
  document.cookie = cName + '=' + cValue;
};

var login = function () {
  var formLogin = $(this).serialize();
  $.post('../../controlador/iniciar_sesion.php', formLogin, respuestaLogin).fail(errorLogin);
  return false;
};

function respuestaLogin(datosDevueltos, status) {
  if (datosDevueltos == 'falso_e') {
    alert('Ooooops! Este email no está registrado en la App-web');
  }else if (datosDevueltos == 'falso_c') {
    alert('Contraseña Errónea');
  }else if (datosDevueltos == 'cierto') {
    location.assign('principal.php');
  };
};

function errorLogin(xhr, status, error) {
  console.log(error);
};

var forget = function () {
  var formForget = $(this).serialize();
  $.post('../../controlador/recuperar.php', formForget, function (datosDevueltos, status) {
    if (datosDevueltos == 'no_existe') {
      alert('El correo introducido no es correcto o no existe');
    }else if (datosDevueltos == 'error_enviar') {
      alert('Ha ocurrido un error al enviar, lo sentimos.');
    }else if (datosDevueltos == 'conectado') {
      alert('Ya estás conectado');
    }else if (datosDevueltos == 'enviado') {
      alert('Te hemos enviado un correo para que puedas modificar tu contraseña');
    }
  }).fail(function (xhr, status, msg) {
    console.log(msg);
  });

  return false;
};

function noVacio(str) {
  if (str == undefined) {
    return false;
  } else {
    var x = (str.replace('\n', ' ')).replace('/\s\s+/', ' ');
    var x = x.trim();

    if (x) {
      return true;
    }else {
      return false;
    };
  };
};

var register = function () {
  var exito = true;
  $('#register-form .form-control').each(function () {
    if (!noVacio($(this).val())) {
      exito = false;
    };
  });

  var email = $('#register-form input[name=email]').val();
  var pass = $('#register-form input[name=password]').val();
  var cpass = $('#register-form input[name="confirm-password"]').val();
  exito = (exito) ? (pass == cpass) : false;
  if (exito) {
    var re = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
    var isPass = re.test(pass);
    var re2 = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9._-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    var isEmail = re2.test(email);
    if (isPass && isEmail) {
      var formRegistro = $('#register-form').serialize();
      var enlace = '../../controlador/registrar.php';
      $.post(enlace, formRegistro, respuestaRegistro).fail(errorRegistro);
    } else {
      alert('Recuerda que son más de 8 caracteres y \nal menos una mayúscula y un número');
    };
  }else {
    alert('Las contraseñas no coinciden');
  };

  return false;
};

function respuestaRegistro(datosDevueltos, status) {
  if (datosDevueltos == 'existe') {
    alert('Este email ya ha sido registrado');
  }else if (datosDevueltos == 'registrado') {
    location.assign('principal.php');
  }else if (datosDevueltos == 'error_codigoAlta') {
    alert('Este usuario no está dado de alta en la web');
  };
};

function errorRegistro(xhr, status, msg) {
  console.log(msg);
};

function ver(id) {
  $('#register-form #' + id).attr('type', 'text');
};

function ocultar(id) {
  $('#register-form #' + id).attr('type', 'password');
};

$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();
  if (screen.height < 525) {
    $('body').css('overflow', 'scroll');
  };

  var tabla = $('#login-form input[type="hidden"]').val();
  setCookie('tabla', tabla, 1);

  $('.forgot-pass').click(function (event) {
    $('.pr-wrap').toggleClass('show-pass-reset');
  });

  $('.pass-reset-submit').click(function (event) {
    $('.pr-wrap').removeClass('show-pass-reset');
  });

  $('#login-form-link').click(function (e) {
    $('#login-form').delay(100).fadeIn(100);
    $('#register-form').fadeOut(100);
    $('#register-form-link').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
  });

  $('#register-form-link').click(function (e) {
    $('#register-form').delay(100).fadeIn(100);
    $('#login-form').fadeOut(100);
    $('#login-form-link').removeClass('active');
    $(this).addClass('active');
    e.preventDefault();
  });

  $('#login-form').submit(login);
  $('#register-form').submit(register);
  $('#forget-form').submit(forget);
});
