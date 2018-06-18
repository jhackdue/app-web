<!DOCTYPE html>
<html>
  <head>
    <?php
        session_start();
        if (isset($_SESSION["administrador"])) {
            header("location:administrador.php");
        };
    ?>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/ok_hand.png">
    <title>Administrador App-Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <!-- <link rel="stylesheet" href="css/index.css"> -->
    <!-- <link rel="stylesheet" href="css/font-awesome.min.css"> -->
  </head>
  <body>
    <header>
    <a href='index.php'><img src="../img/app/ok_hand.png" alt="ok_hand.png" title="administrador"></a>
    <h1>ADMINISTRADOR</h1>
    </header>
    <section>
      <fieldset>
        <legend>Login</legend>
        <form id="form" action="../../controlador/administracion/iniciar_sesion.php" method="post">
          <label for="email">Email</label>
          <input type="email" name="email" placeholder="email" id="email" required>
          <label for="pass">Contraseña</label>
          <input type="password" name="password" placeholder="password" id="pass" required>
          <input type="hidden" name="enviado" value="enviado">
          <button type="submit" class="btn btn-block btn-success">
            INICIAR SESIÓN
          </button>
        </form>
      </fieldset>
      <a href="registro.php" id="registro"><input type="button" name="registro" value="Registrate"></a>
    </section>
    <script src="../js/jquery.min.js" charset="utf-8"></script>
    <script  type="text/javascript" src="../js/administracion/inicio.js" charset="utf-8"></script>
    <noscript>Tu navegador solicita acceso a Javascript</noscript>
  </body>
</html>
