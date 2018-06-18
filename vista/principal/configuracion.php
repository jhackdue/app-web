<!DOCTYPE html>
<html>
  <head>
    <?php
        session_start();
        if (!isset($_SESSION["conectado"])) {
            header("location:../index.php");
            die('No hay sesión creada');
        };
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/logo.png">
    <meta name="description" content="Página de configuracion de la aplicación web sobre apuntes de matemáticas">
    <meta name="author" content="Jhack Nicole Dueñas Povis">
    <title>Configuracion: Nombre Usuario</title>
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/bootstrap-datetimepicker.css">
    <link rel="stylesheet/less" type="text/css" href="../css/configuracion.less">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">

  </head>
  <body>
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

      <div class="collapse navbar-collapse" id="despliega">

        <ul class="nav navbar-nav navbar-right text-center">
          <li><a href="configuracion.php"><i class="fa fa-cog" aria-hidden="true"></i></a></li>
          <li><a href="../../controlador/cerrar.php"><i class="fa fa-power-off" aria-hidden="true"></i></a></li>
        </ul>
      </div>
    </nav>

    <div class="container-fluid">

      <div class="row">

        <div class="col-xs-0 col-sm-2 col-md-2 col-lg-2" id="left">
          <ul class="nav nav-stacked nav-pills" >
            <li class="active"><a data-toggle="pill" href="#datos">Datos</a></li>
            <!-- <li><a data-toggle="pill" href="#descargas">Perfil</a></li> -->
          </ul>
        </div>

        <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10" id="right">
          <!-- <div class="row"> -->
            <!-- <div class="col-md-12 tab-content"> -->
          <div class="tab-content">

            <div class="text-center">
              <ul class="nav nav-pills nav-justified" id="nav768">
                <li class="active"><a data-toggle="pill" href="#datos">Datos</a></li>
                <!-- <li><a data-toggle="pill" href="#descargas">Descargas</a></li> -->
              </ul>
            </div>

            <div id="datos" class="tab-pane fade in active">
              <div class="page-header">
                <h1>Datos Personales <img src="../img/app/configuracion.svg" alt="ajustes" class="img-circle"></h1>
              </div>

              <div class="jumbotron">
                <div class="background-upload">
                  <label for="background" class="btn btn-sm">Cambiar Fondo</label>
                  <input id="background" class="file-input" onchange="cambiar(this.id);" type="file" accept="image/*" name="background"/>
                </div>
                <div id="foto_div">
                  <div class="foto-upload">
                    <label for="foto" class="btn btn-sm"><img alt="camera" src="../img/app/camera2.svg" class="img-circle" title="Cambia de Foto"/></label>
                    <input id="foto" class="file-input" onchange="cambiar(this.id);" type="file" accept="image/*" name="foto"/>
                  </div>
                  <img alt="Foto de Usuario" src="../img/app/usuario.svg" class="img-circle" id="foto_perfil"/>
                </div>
              </div>

              <div class="form-group form-inline">
                <label for="nombre" class="control-label">
                  Nombre:
                </label>
                <div class="pull-right">
                  <input class="form-control" id="nombre" name="nombre" type="text" onfocus="this.value=''" autocomplete="off" />
                  <button type="button" class="btn btn-default" onclick="cambiar('nombre')">Editar</button>
                </div>
                <div class="oculto text-center">
                  <input class="form-control" id="nombre" name="nombre" type="text" onfocus="this.value=''" autocomplete="off" />
                  <button type="button" class="btn btn-default" onclick="cambiar('nombre')">Editar</button>
                </div>
              </div>

              <hr>

              <div class="form-group form-inline">
                <label for="nacimiento" class="control-label">Fecha de Nacimiento</label>
                <div class="pull-right">
                  <div class="input-group date" id="datetimepicker1">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="date" name="nacimiento" class="form-control" id="nacimiento" >
                  </div>
                  <!-- onchange="cambioHora();" -->
                  <button type="button" class="btn btn-default" onclick="cambiar('nacimiento')">Editar</button>
                </div>
                <div class="oculto text-center">
                  <div class="input-group date" id="datetimepicker1">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                    <input type="date" name="nacimiento" class="form-control" id="nacimiento" >
                  </div>
                  <!-- onchange="cambioHora();" -->
                  <button type="button" class="btn btn-default" onclick="cambiar('nacimiento')">Editar</button>
                </div>
              </div>

              <hr>

              <div class="form-group form-inline">
                <label for="grado">Grado</label>
                <div class="pull-right">
                    <select class="form-control" id="grado" name="grado" pattern="(?=.*[A-Z]).{30,}" required>
                      <option value="GRADO EN CIENCIAS MATEMÁTICAS">Grado en Ciencias Matemáicas</option>
                      <option value="GRADO EN INGENIERÍA MATEMÁTICA">Grado en Ingeniería Matemática</option>
                    </select>
                  <button type="button" class="btn btn-default" onclick="cambiar('grado')">Editar</button>
                </div>
                <div class="oculto text-center">
                  <select class="form-control" id="grado" name="grado" pattern="(?=.*[A-Z]).{30,}" required>
                    <option value="GRADO EN CIENCIAS MATEMÁTICAS">Grado en Ciencias Matemáicas</option>
                    <option value="GRADO EN INGENIERÍA MATEMÁTICA">Grado en Ingeniería Matemática</option>
                  </select>
                <button type="button" class="btn btn-default" onclick="cambiar('grado')">Editar</button>
                </div>
              </div>

              <hr>

              <div class="form-group form-inline">
                <label for="curso">Curso</label>
                <div class="pull-right">
                    <select class="form-control" id="curso" name="curso" pattern="(?=.*[A-Z]).{7,}" onclick="return mostrarCurso(this.id);" required>
                      <option value="PRIMERO">1</option>
                      <option value="SEGUNDO">2</option>
                      <option value="TERCERO">3</option>
                      <option class="mat" value="CIENCIAS DE LA COMPUTACIÓN">4 - CIENCIAS DE LA COMPUTACIÓN</option>
                      <option class="mat" value="MATEMÁTICA PURA Y APLICADA I">4 - MATEMÁTICA PURA Y APLICADA I</option>
                      <option class="mat" value="MATEMÁTICA PURA Y APLICADA II">4 - MATEMÁTICA PURA Y APLICADA II</option>
                      <option class="ing" value="ECONOMATEMÁTICA">4 - ECONOMATEMÁTICA</option>
                      <option class="ing" value="TECNOMATEMÁTICA">4 - TECNOMATEMÁTICA</option>
                      <option class="ing" value="GEODESIA">4 - GEODESIA</option>
                    </select>
                  <button type="button" class="btn btn-default" onclick="cambiar('curso')">Editar</button>
                </div>
                <div class="oculto text-center">
                  <select class="form-control" id="curso" name="curso" onclick="return mostrarCurso(this.id);" pattern="(?=.*[A-Z]).{7,}" required>
                    <option value="PRIMERO">1</option>
                    <option value="SEGUNDO">2</option>
                    <option value="TERCERO">3</option>
                    <option class="mat" value="CIENCIAS DE LA COMPUTACIÓN">4 - CIENCIAS DE LA COMPUTACIÓN</option>
                    <option class="mat" value="MATEMÁTICA PURA Y APLICADA I">4 - MATEMÁTICA PURA Y APLICADA I</option>
                    <option class="mat" value="MATEMÁTICA PURA Y APLICADA II">4 - MATEMÁTICA PURA Y APLICADA II</option>
                    <option class="ing" value="ECONOMATEMÁTICA">4 - ECONOMATEMÁTICA</option>
                    <option class="ing" value="TECNOMATEMÁTICA">4 - TECNOMATEMÁTICA</option>
                    <option class="ing" value="GEODESIA">4 - GEODESIA</option>
                  </select>
                <button type="button" class="btn btn-default" onclick="cambiar('curso')">Editar</button>
                </div>
              </div>

              <hr>

              <div class="form-group form-inline">
                <label for="email" class="control-label">
                  Correo:
                </label>
                <div class="pull-right">
                  <input class="form-control" onfocus="this.value=''" id="email" name="email" type="email" autocomplete="off" />
                  <button type="button" class="btn btn-default" onclick="cambiar('email')">Editar</button>
                </div>
                <div class="oculto text-center">
                  <input class="form-control" onfocus="this.value=''" id="email" name="email" type="email" autocomplete="off" />
                  <button type="button" class="btn btn-default" onclick="cambiar('email')">Editar</button>
                </div>
              </div>
              <!-- acaba aquí -->

            </div>

            <!-- <div id="descargas" class="tab-pane fade">
              <h3>Descargas</h3>
              <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
            </div> -->

          </div>

          <!-- </div> -->

        </div>

      </div>

    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/2.7.2/less.min.js"></script>
    <script src="../js/moment.min.js" charset="utf-8"></script>
    <script src="../js/bootstrap-datetimepicker.min.js" charset="utf-8"></script>
    <script src="../js/configuracion.js" charset="utf-8"></script>
  </body>
</html>
