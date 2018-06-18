<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/ok_hand.png">
    <title>Admin: Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Anton" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    <link rel="stylesheet" href="../css/administracion/registro.css">
  </head>
  <body>
    <div id="titulo">
      <div>Registro de administradores<span><a href="index.php"><img src="../img/app/ok_hand.png"></a></span></div>
    </div>

    <form id="formu_registro" action="../../controlador/administracion/registrar.php" method="post">
      <div id="cuerpo">
      <!-- ............................................................. -->
        <div class="form-group elemento">
          <label for="nombre">Nombre</label>
          <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required>
        </div>
        <div class="form-group elemento">
          <label for="apellidos">Apellidos</label>
          <input type="text" name="apellidos" class="form-control" id="apellidos" placeholder="Apellidos" required>
        </div>
        <div class="form-group elemento">
          <label for="email">Email</label>
          <input type="email" name="email" class="form-control" id="email" placeholder="Email" required>
        </div>
        <div class="form-group elemento">
          <label for="pass">Contraseña</label>
          <input type="password" name="password" class="form-control" id="pass" placeholder="Contraseña" required>
        </div>
      <!-- ............................................................. -->
      </div>
      <!-- ............................................................. -->
      <div id="botones">
        <input type="hidden" name="rellenado" id="formulario_rellenado" class="valid" value="rellenado">
        <input type="submit" class="btn btn-success" name="enviar" value="Registrarse">
        <input type="reset" class="btn btn-primary" id="limpiar" name="reset" value="Limpiar">
        <!-- ............................................................. -->
      </div>
    </form>
    <script src="../js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="../js/administracion/registro.js" charset="utf-8"></script>
    <noscript>Necesitas javascript</noscript>
  </body>
</html>
