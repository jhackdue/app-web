<!DOCTYPE html>
<html lang="es">
  <head>
    <?php
        if (!isset($_COOKIE["modificar"])) {
            header('location:error_modificar.php');
            die('No existe la coockie "modificar"');
        }
    ?>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/logo.png">
    <title>App-Web | Modificar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <meta name="author" content="Jhack Nicole Dueñas Povis">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    <link rel="stylesheet/less" type="text/css" href="../css/recuperar.less">
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="logo">
            <a href="../index.php"><img src="../img/app/logonombre.png" alt="Logo Inicio" title="Ir a Inicio" class="img-rounded"/></a>
          </div>

          <h1 class="text-center">Modifica tu contraseña</h1>

          <form class='formulario' action='../../controlador/modificar.php' method="post">

            <div class="form-group">
              <input type="password" class="form-control" name="password" id="password" placeholder="nueva contraseña" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
              <label for="password">Nueva Contraseña</label>
            </div>

            <div class="form-group">
              <input type="password" class="form-control" id="repetir" name="repetir" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" placeholder="repetir contraseña" required/>
              <label for="repetir">Repetir contraseña</label>
            </div>

            <input type="hidden" name="email" value="<?php echo $_COOKIE['modificar']; ?>">
            <input type="hidden" name="tabla" value="<?php echo $_COOKIE['tabla']; ?>">
            <button type="submit" class="btn btn-info btn-sm">Recuperar</button>
          </form>

          <div class="alert alert-dismissable alert-info">
            <h4><strong>Recuerda!</strong></h4>
             La contraseña debe tener al menos 8 caracteres, una mayúscula y un número.
          </div>

          <div class="alert alert-dismissable alert-danger">
            <h4><strong>Oooooops!</strong></h4>
             Las contraseñas no coinciden.
          </div>

          <div class="alert alert-dismissable alert-warning">
            <h4><strong>Ooooooops!</strong></h4>
            Parece que ha ocurrido algún error al intentar modificar tu contraseña.<br>
            Por favor, inténtalo más tarde. ¡Gracias!
          </div>

        </div>
      </div>
    </div>
    <script src="../js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/2.7.2/less.min.js"></script>
    <script src="../js/modificar.js" charset="utf-8"></script>
    <noscript>Necesitas javascript</noscript>
  </body>
</html>
