<?php
    $correcto = (isset($_GET['perfil']) && ($_GET['perfil']=='estudiantes' || $_GET['perfil']=='profesores')) ? true : false;
    $correcto2 = (isset($_GET['usuario']) && (is_numeric($_GET['usuario'])))? true : false;
    $correcto = ($correcto) ? ($correcto2) : false;
    if (!$correcto) {
      header('location:index.php');
      die('no es correcto');
    };
    $perfil = $_GET['perfil'];
    $usuario = $_GET['usuario'];
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/logo.png">
    <meta name="description" content="Página principal de la aplicación web sobre apuntes de matemáticas">
    <meta name="author" content="Jhack Nicole Dueñas Povis">
    <title>App-Web | Perfil</title>
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet/less" type="text/css" href="../css/verPerfil.less">
    <link href="https://fonts.googleapis.com/css?family=Roboto:500" rel="stylesheet">
  </head>
  <body>
    <?php
    session_start();
    if (isset($_SESSION['online']) || isset($_SESSION['conectado'])): ?>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#despliega">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php"><img src="../img/app/logonombre.png" alt="inicio" title="Inicio" id="logo"></a>
        </div>

      <div class="collapse  navbar-collapse" id="despliega">
        <ul class="nav navbar-nav navbar-right text-center">
          <li><a href="configuracion.php"><i class="fa fa-cog" aria-hidden="true"></i></a></li>
          <li><a href="../../controlador/cerrar.php"><i class="fa fa-power-off" aria-hidden="true"></i></a></li>
        </ul>
      </div>
    </nav>
    <?php else: ?>
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
    <?php endif; ?>


    <div class="container-fluid">
      <div class="row" id="div_uno">
        <div class="col-md-12">

          <div class="jumbotron" id="background_perfil">
            <img alt="Foto de Usuario" src="../img/app/usuario.svg" class="img-circle" id="foto_perfil" />
          </div>

          <ul class="nav nav-pills nav-justified">
            <li class="active text-center"><a href="#publicaciones" data-toggle="pill">Información Académica</a></li>
            <li class="text-center"><a href="#info" data-toggle="pill">Información Personal</a></li>
          </ul>


        </div>
      </div>

      <div class="row" id="cont-ipad-768">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="tab-content">

            <div class="tab-pane fade in active" id="publicaciones">
              <div id="contenido_publicaciones">
                <div class="col-md-12">
                  <h5 class="text-center text-info">
                    Documentos Subidos
                  </h5>
                  <p>&nbsp;</p>
                  <div id="subidos">

                  </div>
                </div>
              </div>
            </div>

            <div class="tab-pane fade" id="info">
              <?php if ($perfil == 'estudiantes'): ?>
                <div id="contenido_info">
                  <div class="col-md-12">
                    <div class="jumbotron text-center">
                      <dl>
                				<dt>
                					Nombre
                				</dt>
                				<dd id="nombre">

                				</dd>
                        <dt>
                          Perfil
                        </dt>
                        <dd id="perfil">

                        </dd>
                				<dt>
                					Nacimiento
                				</dt>
                				<dd id="nacimiento">

                				</dd>
                        <dt>
                          Email
                        </dt>
                				<dd id="email">

                				</dd>
                				<dt>
                          Grado
                				</dt>
                				<dd id="grado">

                				</dd>
                				<dt >
                					Curso
                				</dt>
                				<dd id="curso">

                				</dd>
                        <dt>
                					Valoración
                				</dt>
                				<dd id="valoracion">

                				</dd>
                			</dl>
                    </div>
                  </div>
                </div>
              <?php else: ?>
                <div id="contenido_info">
                  <div class="col-md-12">
                    <div class="jumbotron text-center">
                      <dl>
                        <dt>
                          Nombre
                        </dt>
                        <dd id="nombre">
                        </dd>
                        <dt>
                          Perfil
                        </dt>
                        <dd id="perfil">
                        </dd>
                        <dt>
                          Departamento
                        </dt>
                        <dd id="departamento">
                        </dd>
                        <dt>
                          Email
                        </dt>
                        <dd id="email">
                        </dd>
                        <dt>
                          Grado
                        </dt>
                        <dd id="grado">
                        </dd>
                        <dt>
                          Valoración
                        </dt>
                        <dd id="valoracion">
                        </dd>
                      </dl>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 text-center" id="div_dos">
          <div id="contenido_publicaciones">
            <div class="col-md-12">
              <h3 class="text-center text-info">
                Documentos Subidos
              </h3>
              <div id="subidos2" style="margin-top:0.1em;">
              </div>
            </div>
          </div>

        </div>

        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" id="div_tres">
          <?php if ($perfil == 'estudiantes'): ?>
            <div id="contenido_info">
              <div class="col-md-12">
                <div class="jumbotron text-center">
                  <dl>
                    <dt>
                      Nombre
                    </dt>
                    <dd id="nombre">
                    </dd>
                    <dt>
                      Perfil
                    </dt>
                    <dd id="perfil">
                    </dd>
                    <dt>
                      Nacimiento
                    </dt>
                    <dd id="nacimiento">
                    </dd>
                    <dt>
                      Email
                    </dt>
                    <dd id="email">
                    </dd>
                    <dt>
                      Grado
                    </dt>
                    <dd id="grado">
                    </dd>
                    <dt>
                      Curso
                    </dt>
                    <dd id="curso">
                    </dd>
                    <dt>
                      Valoración
                    </dt>
                    <dd id="valoracion">
                    </dd>
                  </dl>
                </div>
              </div>
            </div>
          <?php else: ?>
            <div id="contenido_info">
              <div class="col-md-12">
                <div class="jumbotron text-center">
                  <dl>
                    <dt>
                      Nombre
                    </dt>
                    <dd id="nombre">
                    </dd>
                    <dt>
                      Perfil
                    </dt>
                    <dd id="perfil">
                    </dd>
                    <dt>
                      Departamento
                    </dt>
                    <dd id="departamento">

                    </dd>
                    <dt>
                      Email
                    </dt>
                    <dd id="email">

                    </dd>
                    <dt>
                      Grado
                    </dt>
                    <dd id="grado">

                    </dd>
                    <dt >
                      Valoración
                    </dt>
                    <dd id="valoracion">

                    </dd>
                  </dl>
                </div>
              </div>
            </div>

          <?php endif; ?>
        </div>
      </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/2.7.2/less.min.js"></script>
    <script src="../js/verPerfil.js" charset="utf-8"></script>
    <noscript>Necesitas javascript</noscript>
  </body>
</html>
