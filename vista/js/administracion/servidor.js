var ocultar = function () {
  var url = document.URL;
  if (url.indexOf('actualizar') > -1) {
    $('#visible').hide();
    $('#invisible').show();
  } else {
    $('#invisible').hide();
    $('#visible').show();
  };
};

$(document).ready(function () {
  var url = document.URL;
  if (url.indexOf('actualizar') > -1) {
    $('#visible').hide();
    $('#invisible').show();
  } else {
    $('#invisible').hide();
    $('#visible').show();
  };
});
