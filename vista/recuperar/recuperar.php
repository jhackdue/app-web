<!DOCTYPE html>
<html lang="es">
  <head>
    <?php
      if (!isset($_COOKIE['tabla'])) {
        header('location:../index.php');
        die('no hay tabla creada');
      };
    ?>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/logo.png">
    <title>App-Web | Recuperar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>

    <meta name="description" content="Recuperar contraseña de la App-Web">
    <meta name="keywords" content="Andrew Forrester,Noun Project,Jhack Nicole Dueñas Povis, App-Web">
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
            <a style="cursor:pointer;" onclick="Volver();"><img src="../img/app/logonombre.png" alt="Logo Inicio" title="Ir a Inicio" class="img-rounded"/></a>
          </div>

          <h1 class="text-center"><span><img src="../img/app/olvido.png" alt="Forget by Andrew Forrester from the Noun Project"></span> Recuperar Contraseña</h1>

          <form role="form" class="formulario" action="../../controlador/recuperar.php" method="post">
            <div class="form-group">
              <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@ucm.es" required/>
              <label for="email">Introduce tu email</label>
            </div>
            <?php
              $tabla = password_hash('estudiantes', PASSWORD_DEFAULT, array('cost' =>15));
            ?>
            <input type="hidden" name="tabla" value=<?php echo "$tabla"; ?>>
            <button type="submit" class="btn btn-info btn-sm">Recuperar</button>
          </form>

          <div class="alert alert-dismissable alert-info">
            <h4><strong>Enviado!</strong></h4>
             Te hemos enviado un enlace para que puedas recuperar tu cuenta.<br>
             * Por favor, revisa tu bandeja de correo no deseado o tu carpeta de spam
          </div>

          <div class="alert alert-dismissable alert-danger">
            <h4><strong>Oooooops!</strong></h4>
             Este usuario no está registrado en la App-Web.<br>
             Por favor, <a href="../registro/registro.php">regístrate</a>.<br>
          </div>

          <div class="alert alert-dismissable alert-warning">
            <h4><strong>Oooops!</strong></h4>
            Parece que ha ocurrido algún error al intentar enviarte el correo.<br>
            Por favor, inténtalo más tarde. ¡Gracias!
          </div>
        </div>
      </div>
    </div>
    <script src="../js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/2.7.2/less.min.js"></script>
    <script src="../js/recuperar.js" charset="utf-8"></script>
    <noscript>Necesitas javascript</noscript>
  </body>
</html>
