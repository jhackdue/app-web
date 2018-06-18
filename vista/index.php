<!DOCTYPE html>
<html lang="es">
  <head>
    <?php
        session_start();
        if (isset($_SESSION["conectado"])) {
            header("location:principal/index.php");
            die('Ya hay una sesión creada');
        };
    ?>
    <meta charset="utf-8">
    <meta name="description" content="Aplicacion web para subir o descargar apuntes de la Facultad de Matemáticas">
    <meta name="author" content="Jhack Nicole Dueñas Povis">
    <link rel="shortcut icon" type="img/x-icon" href="img/app/logo.png">
    <title>App-Web</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
     <link href="https://fonts.googleapis.com/css?family=Roboto:500i" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet/less" type="text/css" href="css/index.less">
  </head>
  <body>
    <nav class="navbar navbar-fixed-top" role="navigation">
      <div class="navbar-header">

        <a href="#modalcontainer1" class="btn btn-sm navbar-toggle" data-toggle="modal" type="button">
          <span class="sr-only">Toggle navigation</span>
          <span class="glyphicon glyphicon-log-in"></span>
        </a>
        <a class="navbar-brand" onclick="ir('div_uno_container');"><img src="img/app/logo.png" alt="logo" class="img-circle"></a>
      </div>

      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li><a id="modal1" href="#modalcontainer1"  data-toggle="modal" role="button">Entrar</a></li>
        </ul>
      </div>
    </nav>

    <!-- Modal! -->
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
              <a href='recuperar/recuperar.php' class="btn btn-link btn-sm btn-block" type="button" onclick="Olvido();">¿Olvidaste tu contraseña?</a>
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
      <div id="div_uno_container"></div>
      <div class="row" id="div_uno">
        <div class="col-md-12">
          <div class="jumbotron">
            <h1>COMPARTE APUNTES O CONSIGUE AYUDA</h1>
            <h3>ASÍ DE FÁCIL</h3>
            <a class="btn btn-info btn-lg" onclick="Registro();">Regístrate</a>
          </div>
          <div class="btn-group btn-group-xs">
            <a id="contacto" class="btn btn-link" type="button" onclick="PonerCookie();">Contacto</a>
          </div>
        </div>
      </div>

      <div class="row" id="div_dos">
        <div class="col-md-12">
          <a onclick="ir('div_dos_container')" class="same_page">¿Cómo funciona?</a>
          <div id="div_dos_container"></div>
          <div  class="page-header">
            <h1 class="text-center">
              PUEDES AYUDAR A TUS COMPAÑEROS SUBIENDO TUS MEJORES APUNTES Y SI QUIERES, PUEDES PEDIRLES AYUDA A ALGUNO DE ELLOS O INCLUSO A ALGÚN PROFESOR
            </h1>
          </div>

          <div class="row" >
            <div class="col-md-4 imagen">
              <i class="fa fa-cloud-upload fa-5x" aria-hidden="true"></i><br>
              <span class="lead text-center">SUBE TUS APUNTES</span>
            </div>

            <div class="col-md-4 imagen">
              <i class="fa fa-cloud-download fa-5x" aria-hidden="true"></i><br>
              <span class="lead text-center">DESCARGA APUNTES DE OTROS</span>
            </div>

            <div class="col-md-4 imagen">
              <i class="fa fa-users fa-5x" aria-hidden="true"></i><br>
              <span class="lead text-center">CONTACTA CON GENTE QUE TE VA A AYUDAR</span>
            </div>
          </div>
        </div>
      </div>

      <div class="row" id="div_tres">
        <div class="col-md-12">
          <a class="same_page" onclick="ir('div_tres_container')">¡Entra y descarga apuntes!</a>
          <div id="div_tres_container"></div>
          <h1 class="text-center">
            ECHA UN VISTAZO  <i class="fa fa-eye" aria-hidden="true"></i><br>
            ¿CONOCES A ALGUIEN?
          </h1>
          <div class="row">
            <div class="col-md-3">
            </div>

            <div class="col-md-6">
              <div class="row">
                <div class="col-md-6">
                  <p class="lead text-center">
                    <a href="principal/usuarios.php?user=profesores">
                      <img src="img/app/profe.png" alt="Profesores" title="Profes">
                    </a><br>
                    Profesores
                  </p>
                </div>

                <div class="col-md-6">
                  <p class="lead text-center">
                    <a href="principal/usuarios.php?user=estudiantes">
                      <img src="img/app/alumno.png" alt="Alumnos" title="Alumnos">
                    </a><br>
                    Alumnos
                  </p>
                </div>
              </div>
              <p class="lead text-center">
                <a class="btn btn-info btn-lg" onclick="Registro();">Regístrate</a>
                <a class="btn btn-success btn-lg" onclick="ir('div_uno_container');">Volver Arriba</a>
              </p>
            </div>

            <div class="col-md-3">
            </div>
          </div>
        </div>
      </div>
      <footer>
          <address class="text-center">
            <strong><span class="glyphicon glyphicon-copyright-mark"></span> Jhack Nicole Dueñas Povis</strong><br>
            Universidad Complutense de madrid<br>
            <a href="http://matematicas.ucm.es/"  target="_blank" onclick="PonerCookie();">Facultad de ciencias matemáticas</a><br>
          </address>
      </footer>
    </div>
    <div id="barraaceptacion">
      <div class="inner">
        Solicitamos su permiso para obtener datos estadísticos de su navegación en esta web, en cumplimiento del Real Decreto-ley 13/2012. Si continúa navegando consideramos que acepta el uso de cookies.
        <a class="ok" onclick="PonerCookie();">OK</a> | <a href="http://politicadecookies.com" target="_blank" class="info">Más información</a>
      </div>
    </div>

    <script src="js/jquery.min.js" charset="utf-8"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/2.7.2/less.min.js"></script>
    <script  type="text/javascript" src="js/inicio.js" charset="utf-8"></script>
    <noscript>Tu navegador solicita acceso a Javascript</noscript>
  </body>
</html>
