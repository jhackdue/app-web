<?php
    session_start();
    if (isset($_SESSION["conectado"])) {
        header("location:index.php");
        die('Ya hay una sesión creada');
    }elseif (isset($_SESSION["online"])) {
      header("location:../profesores/principal.php");
    };

    $correcto = (isset($_GET['user']) && ($_GET['user']=='estudiantes' || $_GET['user']=='profesores')) ? true : false;
    if (!$correcto) {
      header("location:../index.php");
      die('El get no es válido');
    };

    $perfil = $_GET['user'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="description" content="Aplicacion web para subir o descargar apuntes de la Facultad de Matemáticas">
    <meta name="author" content="Jhack Nicole Dueñas Povis">
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/logo.png">
    <title>App-Web | Usuarios</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
     <link href="https://fonts.googleapis.com/css?family=Roboto:500i" rel="stylesheet">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet/less" type="text/css" href="../css/usuarios.less">
  </head>
  <body>
    <nav class="navbar navbar-fixed-top" role="navigation">
      <div class="navbar-header">

        <a href="#modalcontainer1" class="btn btn-sm navbar-toggle" data-toggle="modal" type="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="glyphicon glyphicon-log-in"></span>
        </a>
        <a class="navbar-brand" href="../index.php"><img src="../img/app/logo.png" alt="logo" class="img-circle"></a>
      </div>

      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a id="modal1" href="#modalcontainer1"  data-toggle="modal" role="button">Entrar</a></li>
        </ul>
      </div>
    </nav>
    <div class="modal fade" id="modalcontainer1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel">Ingresa en la App-Web</h4>
          </div>

          <div class="modal-body">
            <form role="form" id="form" action="../controlador/iniciar_sesion.php" method="post">
              <div class="form-group">
                <label for="email">Correo</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required/>
              </div>

              <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required/>
              </div>
              <?php
                $tabla = password_hash('estudiantes', PASSWORD_DEFAULT, array('cost' =>15));
              ?>
              <input type="hidden" name="tabla" value=<?php echo "$tabla"; ?>>
              <span class="help-block text-center">
                La contraseña contiene al menos 8 caracteres, una mayúscula y un número.
              </span>
              <a href='../recuperar/recuperar.php' class="btn btn-link btn-sm btn-block" type="button" onclick="Olvido();">¿Olvidaste tu contraseña?</a>
              <button type="submit" class="btn btn-block btn-success">
                INICIAR SESIÓN
              </button>
            </form>
          </div>
          <div class="modal-footer">
            <p class="text-center text-info">¿Aún no registrado?</p>
            <a class="btn btn-primary btn-block" type="button" onclick="Registro();">Regístrate</a>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid">

      <div class="row">

        <div class="col-xs-0 col-sm-3 col-md-3 col-lg-3" id="left">
          <div id="formulario_controladoresL">
            <div class="form-group">
              <label for="grado" class="control-label">Grado</label>
              <select class="form-control" onchange="buscar('', 1);" id="grado" name="grado" pattern="(?=.*[A-Z]).{30,}" required>
                <option value="GRADO EN CIENCIAS MATEMÁTICAS">Grado en Ciencias Matemáicas</option>
                <option value="GRADO EN INGENIERÍA MATEMÁTICA">Grado en Ingeniería Matemática</option>
              </select>
            </div>

            <input type="hidden" name="get" value=<?php echo "$perfil"; ?>>

            <div class="form-group">
              <label for="perfilFichero" class="control-label">Tipo</label>
              <select class="form-control" onchange="buscar('', 1);" id="perfilFichero" name="perfilFichero" pattern="(?=.*[A-Z]).{7,}" required>
                <option value="teoria">Teoría</option>
                <option value="examen">Exámen</option>
                <option value="practica">Práctica</option>
              </select>
            </div>
          </div>
        </div>

        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9" id="right">
          <div id="controladores">
            <div id="formulario_controladoresR">
              <div class="form-group">
                <label for="grado" class="control-label">Grado</label>
                <select class="form-control" onchange="buscar('',1);" id="grado" name="grado" pattern="(?=.*[A-Z]).{30,}" required>
                  <option value="GRADO EN CIENCIAS MATEMÁTICAS">Grado en Ciencias Matemáicas</option>
                  <option value="GRADO EN INGENIERÍA MATEMÁTICA">Grado en Ingeniería Matemática</option>
                </select>
              </div>

              <input type="hidden" name="get" value=<?php echo "$perfil"; ?>>

              <div class="form-group">
                <label for="perfilFichero" class="control-label">Tipo</label>
                <select class="form-control" onchange="buscar('', 1);" id="perfilFichero" name="perfilFichero" pattern="(?=.*[A-Z]).{7,}" required>
                  <option value="teoria">Teoría</option>
                  <option value="examen">Exámen</option>
                  <option value="practica">Práctica</option>
                </select>
              </div>
            </div>
          </div>

          <input type="text" name="search" id="busqueda" oninput="buscar('', 1);" placeholder="Busca..">
          <div class="col-xs-12 col-sm-12 col-md-12" id="pieBusqueda">

          </div>


        </div>

      </div>

    </div>


    <script src="../js/jquery.min.js" charset="utf-8"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/2.7.2/less.min.js"></script>
    <script  type="text/javascript" src="../js/usuarios.js" charset="utf-8"></script>
    <noscript>Tu navegador solicita acceso a Javascript</noscript>
  </body>
</html>
