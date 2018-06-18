<!DOCTYPE html>
<html>
  <head>
    <?php
        session_start();
        if (!isset($_SESSION["administrador"])) {
            header("location:index.php");
        }
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/ok_hand.png">
    <title>Registro Servidor Mail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Anton" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../css/administracion/hoja.css">
    <link rel="stylesheet" type="text/css" href="../css/administracion/registro_correo.css">
  </head>
  <body>
    <header>
      <a href="administrador.php"><i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i></a>
      <a href="cierre.php"><i class="fa fa-power-off fa-2x" aria-hidden="true"></i></a>
    </header>
    <div id="titulo">
      <div>Registrar servidor mail<span><a href="index.php"><img src="../img/app/ok_hand.png"></a></span></div>
    </div>
    <div id="visible">
      <?php
        require_once '../../controlador/administracion/reg_mail.php';
      ?>
    </div>
    <div id="invisible">
      <h1>Actualizar</h1>
      <?php
      // $consulta->act('servidor_mail');
      ?>
      <form class="" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

      <table width="80%" border="0" align="center">
        <tr >
          <td class="primera_fila">Id</td>
          <td class="primera_fila">Nombre</td>
          <td class="primera_fila">Accion</td>
          <td class="primera_fila">Email</td>
          <td class="primera_fila">Password</td>
          <td class="primera_fila">Puerto</td>
          <td class="primera_fila">Host</td>
          <td class="primera_fila">Asunto</td>
          <td class="primera_fila">Mensaje</td>
          <td class="sin">&nbsp;</td>
          <td class="sin">&nbsp;</td>
          <td class="sin">&nbsp;</td>
        </tr>
        <tr>
          <td><input type='text' name='id' size='5' class='centrado' value="<?php echo $_GET['id'] ?>"></td>
          <input type="hidden" name="old" value="<?php echo $_GET['id'] ?>">
          <td><input type='text' name='nombre' size='5' class='centrado' value="<?php echo $_GET['nombre'] ?>"></td>
          <td><input type='text' name='accion' size='5' class='centrado' value="<?php echo $_GET['accion'] ?>"></td>
          <td><input type='email' name='email' size='5' class='centrado' value="<?php echo $_GET['email'] ?>"></td>
          <td><input type='text' name='password' size='5' class='centrado' value="<?php echo $_GET['password'] ?>"></td>
          <td><input type='number' name='puerto' class='centrado' value='<?php echo $_GET['puerto'] ?>'></td>
          <td><input type='text' name='host' size='5' class='centrado' value="<?php echo $_GET['host'] ?>"></td>
          <td><input type='text' name='asunto' size='5' class='centrado' value="<?php echo $_GET['asunto'] ?>"></td>
          <td><input type='text' name='mensaje' size='5' class='centrado' value="<?php echo $_GET['mensaje'] ?>"></td>
          <td class='bot'><input type='submit' name='actualizado' id='actualizado' value='Actualizar'></td>
        </tr>
        <?php
          // $consulta2->cerrar_conexion();
          $consulta->cerrar_conexion();
        ?>
      </table>
    </form>
    </div>

    <script src="../js/jquery.min.js" charset="utf-8"></script>
    <script src="../js/administracion/servidor.js" charset="utf-8"></script>
  </body>
</html>
