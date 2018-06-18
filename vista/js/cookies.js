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
}
