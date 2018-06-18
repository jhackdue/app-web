<?php
  session_start();
  // porque tenemos que indicarle la sesión que queremos
  // en el navegador actual. Es decir, reanuda las sesiones.

  session_destroy();
  //destruimos la sesion

  header("location:index.php");
?>
