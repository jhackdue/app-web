<?php
  session_start();
  if (!isset($_SESSION["online"])) {
      header("location:../index.php");
      die('No hay sesión creada');
  }elseif (isset($_COOKIE['modificar'])){
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
    setcookie('modificar', $_COOKIE['modificar'], time()-1, '/~jhack/tfg/', $domain, false);
  };
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/logo.png">
    <meta name="description" content="Página principal de la aplicación web sobre apuntes de matemáticas">
    <meta name="author" content="Jhack Nicole Dueñas Povis">
    <title> App-Web: Nombre Usuario</title>
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet/less" type="text/css" href="../css/main.less">
    <link href="https://fonts.googleapis.com/css?family=Roboto:500" rel="stylesheet">
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
        <a class="navbar-brand" href="../index.php"><img src="../img/app/logonombre.png" alt="inicio" title="Inicio" id="logo"></a>
        </div>

      <div class="collapse navbar-collapse" id="despliega">
        <ul class="nav navbar-nav navbar-right text-center">
          <li><a href="configuracion.php"><i class="fa fa-cog" aria-hidden="true"></i></a></li>
          <li><a href="../../controlador/cerrar.php"><i class="fa fa-power-off" aria-hidden="true"></i></a></li>
        </ul>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row" id="div_uno">
        <div class="col-md-12">

          <div class="jumbotron" id="background_perfil">
            <img alt="Foto de Usuario" src="../img/app/usuario.svg" class="img-circle" id="foto_perfil" />
            <p style="color:white;" id='nombre_sesion'>NOMBRE USUARIO CONECTADO</p>
          </div>

          <ul class="nav nav-pills nav-justified">
            <li class="active text-center"><a href="#publicaciones" data-toggle="pill">Publicaciones</a></li>
            <li class="text-center"><a href="#apuntes" data-toggle="pill">Apuntes</a></li>
          </ul>


        </div>
      </div>

      <div class="row" id="cont-ipad-768">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="tab-content">

            <div class="tab-pane fade in active" id="publicaciones">
              <div id="contenido_publicaciones">
                <ul class="nav nav-tabs nav-justified" id="nav_publicaciones">
                  <li class="active"><a data-toggle="tab" href="#documentos">Documentos</a></li>
                  <li><a data-toggle="tab" href="#contacta">Contacta</a></li>
                  <li><a data-toggle="tab" href="#recibidos">Mensajes Recibidos <span class="badge pull-right" id="msgRecibidos">0</span></a></li>
                </ul>

                <div class="tab-content">
                  <div id="documentos" class="tab-pane fade in active">
                    <div class="form-group col-xs-12 col-sm-12 col-md-12" style="margin:0;margin-top:0.7em;">
                      <label for="actualizarDocumentos768" class="control-label col-xs-3 col-sm-3">Actualiza</label>
                      <div class="col-xs-9 col-sm-9">
                        <button type="button" id="actualizarDocumentos768" class="form-control btn btn-sm btn-primary">
                          Actualizar documentos
                        </button>
                      </div>
                    </div>
                    <div class="form-group col-xs-12 col-sm-12 col-md-12" style="margin:0;margin-top:0.4em;">
                      <label for="busqueda768" class="control-label col-xs-3 col-sm-3">
                        Buscar:
                      </label>
                      <div class="col-xs-9 col-sm-9">
                        <input class="form-control" id="busqueda768" name="busqueda" type="text" oninput="documentosPublicados(3,1);" />
                      </div>
                    </div>

                    <div id="pieDocumentos768">
                    </div>
                  </div>
                  <div id="contacta" class="tab-pane fade">

                    <div id="contenido_contactos">
                      <div class="panel panel-info">
                        <div class="panel-heading">
                          <h3 class="panel-title text-center">
                            Contacta con usuarios de la app-web
                          </h3>
                        </div>
                        <div class="panel-body">

                          <div class="form-group form-inline">
                            <label for="perfil" class="control-label">Perfil</label>
                            <div class="text-center">
                              <select class="form-control contactos768" id="perfil" name="tabla" pattern="(?=.*[a-z]).{10,}" onchange="verContactos('.contactos768')" required>
                                <option value="estudiantes">Estudiante</option>
                                <option value="profesores">Profesor/Profesora</option>
                              </select>
                            </div>
                          </div>

                          <div class="form-group form-inline">
                            <label for="nombre" class="control-label">
                              Buscar:
                            </label>
                            <div class="text-center">
                              <input class="form-control contactos768" id="nombre" name="nombre" type="text" oninput="verContactos('.contactos768');" />
                            </div>
                          </div>

                        </div>
                      </div>
                      <div class="text-center" id="pieContactos768">
                      </div>
                    </div>

                  </div>
                  <div id="recibidos" class="tab-pane fade">
                    <div id="contenido_recibidos" class="col-xs-12 col-sm-12 col-md-12">
                      <div class="panel panel-info" style="margin:0; margin-top:0.5em;">
                        <div class="panel-heading">
                          <h3 class="panel-title">
                            Mira los correos que has recibido
                          </h3>
                        </div>
                        <div class="panel-body">

                          <div class="form-group col-xs-12 col-sm-12 col-md-12">
                            <label for="actualizarCorreo768" class="control-label col-xs-12 col-sm-3">Actualiza</label>
                            <div class="col-xs-12 col-sm-9">
                              <button type="button" id="actualizarCorreo768" class="form-control btn btn-sm btn-primary">
                                Actualizar bandeja
                              </button>
                            </div>
                          </div>

                          <div class="form-group col-xs-12 col-sm-12 col-md-12">
                            <label for="perfil" class="control-label col-xs-3 col-sm-3">Perfil</label>
                            <div class="col-xs-9 col-sm-9">
                              <select class="form-control recibidos768" id="perfil" name="tabla" pattern="(?=.*[a-z]).{10,}" onchange="mensajesRecibidos('.recibidos768')" required>
                                <option value="estudiantes">Estudiante</option>
                                <option value="profesores">Profesor/Profesora</option>
                              </select>
                            </div>
                          </div>

                          <div class="form-group col-xs-12 col-sm-12 col-md-12">
                            <label for="nombre" class="control-label col-xs-3 col-sm-3">
                              Buscar:
                            </label>
                            <div class="col-xs-9 col-sm-9">
                              <input class="form-control recibidos768" id="nombre" name="nombre" type="text" oninput="mensajesRecibidos('.recibidos768');" />
                            </div>
                          </div>

                        </div>
                      </div>
                      <div class="text-center" id="pieRecibidos768">
                      </div>

                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="tab-pane fade" id="apuntes">

              <div id="contenido_apuntes">
                <div class="panel panel-info">
                  <div class="panel-heading">
                    <h3 class="panel-title">
                      Sube tus apuntes
                    </h3>
                  </div>
                  <div class="panel-body">
                    <!-- PROBANDO FORMULARIOS -->
                    <div class="form-group form-inline">
                      <label for="grado768" class="control-label">Grado</label>
                      <div class="text-center">
                        <select class="form-control buscar768" id="grado768" name="grado" pattern="(?=.*[A-Z]).{30,}" onchange="verAsignaturas('.buscar768')" required>
                          <option value="GRADO EN CIENCIAS MATEMÁTICAS">Grado en Ciencias Matemáicas</option>
                          <option value="GRADO EN INGENIERÍA MATEMÁTICA">Grado en Ingeniería Matemática</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group form-inline">
                      <label for="curso768" class="control-label">Curso</label>
                      <div class="text-center">
                        <select class="form-control buscar768" id="curso768" name="curso" pattern="(?=.*[A-Z]).{7,}" onchange="verAsignaturas('.buscar768');" required>
                          <option value="PRIMERO">1</option>
                          <option value="SEGUNDO">2</option>
                          <option value="TERCERO">3</option>
                          <option value="CIENCIAS DE LA COMPUTACIÓN">CIENCIAS DE LA COMPUTACIÓN</option>
                          <option value="MATEMÁTICA PURA Y APLICADA I">MATEMÁTICA PURA Y APLICADA I</option>
                          <option value="MATEMÁTICA PURA Y APLICADA II">MATEMÁTICA PURA Y APLICADA II</option>
                          <option value="ECONOMATEMÁTICA">ECONOMATEMÁTICA</option>
                          <option value="TECNOMATEMÁTICA">TECNOMATEMÁTICA</option>
                          <option value="GEODESIA">GEODESIA</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group form-inline">
                      <label for="asignatura" class="control-label">
                        Nombre:
                      </label>
                      <div class="text-center">
                        <input class="form-control buscar768" id="asignatura" name="asignatura" type="text" oninput="verAsignaturas('.buscar768');" />
                      </div>
                    </div>

                    <!-- ACABÓ PRUEBAS -->
                  </div>
                  <div class="panel-footer" id="pieBuscar768">
                  </div>
                </div>
              </div>

            </div>

          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center" id="div_dos">
          <div id="contenido_publicaciones">
            <ul class="nav nav-tabs nav-justified" id="nav_publicaciones">
              <li class="active"><a data-toggle="tab" href="#documentos2">Documentos</a></li>
              <li><a data-toggle="tab" href="#contacta2">Contacta</a></li>
              <li><a data-toggle="tab" href="#recibidos2">Mensajes Recibidos  <span class="badge pull-right" id="msgRecibidos2">0</span></a></li>
            </ul>
            <div class="tab-content">
              <div id="documentos2" class="tab-pane fade in active">
                <div class="form-group col-xs-12 col-sm-12 col-md-12" style="margin:0;margin-top:0.7em;">
                  <label for="actualizarDocumentos" class="control-label col-xs-3 col-sm-3">Actualiza</label>
                  <div class="col-xs-9 col-sm-9">
                    <button type="button" id="actualizarDocumentos" class="form-control btn btn-sm btn-primary">
                      Actualizar documentos
                    </button>
                  </div>
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-12" style="margin:0;margin-top:0.4em;">
                  <label for="busqueda" class="control-label col-xs-3 col-sm-3">
                    Buscar:
                  </label>
                  <div class="col-xs-9 col-sm-9">
                    <input class="form-control" id="busqueda" name="busqueda" type="text" oninput="documentosPublicados(3,1);" />
                  </div>
                </div>
                <div id="pieDocumentos">
                </div>
              </div>
              <div id="contacta2" class="tab-pane fade">

                <div id="contenido_contactos" class="col-xs-12 col-sm-12 col-md-12">
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <h3 class="panel-title">
                        Contacta con usuarios de la app-web
                      </h3>
                    </div>
                    <div class="panel-body">

                      <div class="form-group col-xs-12 col-sm-12 col-md-12">
                        <label for="perfil" class="control-label col-xs-3 col-sm-3">Perfil</label>
                        <div class="col-xs-9 col-sm-9">
                          <select class="form-control contactos" id="perfil" name="tabla" pattern="(?=.*[a-z]).{10,}" onchange="verContactos('.contactos')" required>
                            <option value="estudiantes">Estudiante</option>
                            <option value="profesores">Profesor/Profesora</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group col-xs-12 col-sm-12 col-md-12">
                        <label for="nombre" class="control-label col-xs-3 col-sm-3">
                          Buscar:
                        </label>
                        <div class="col-xs-9 col-sm-9">
                          <input class="form-control contactos" id="nombre" name="nombre" type="text" oninput="verContactos('.contactos');" />
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="text-center" id="pieContactos">
                  </div>
                </div>

              </div>
              <div id="recibidos2" class="tab-pane fade">
                <div id="contenido_recibidos" style="margin:0; margin-top:0.7em;" class="col-xs-12 col-sm-12 col-md-12">
                  <div class="panel panel-info">
                    <div class="panel-heading">
                      <h3 class="panel-title">
                        Mira los correos que has recibido
                      </h3>
                    </div>
                    <div class="panel-body">

                      <div class="form-group col-xs-12 col-sm-12 col-md-12">
                        <label for="actualizarCorreo" class="control-label col-xs-3 col-sm-3">Actualiza</label>
                        <div class="col-xs-9 col-sm-9">
                          <button type="button" id="actualizarCorreo" class="form-control btn btn-sm btn-primary">
                            Actualizar bandeja
                          </button>
                        </div>
                      </div>

                      <div class="form-group col-xs-12 col-sm-12 col-md-12">
                        <label for="perfil" class="control-label col-xs-3 col-sm-3">Perfil</label>
                        <div class="col-xs-9 col-sm-9">
                          <select class="form-control recibidos" id="perfil" name="tabla" pattern="(?=.*[a-z]).{10,}" onchange="mensajesRecibidos('.recibidos')" required>
                            <option value="estudiantes">Estudiante</option>
                            <option value="profesores">Profesor/Profesora</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group col-xs-12 col-sm-12 col-md-12">
                        <label for="nombre" class="control-label col-xs-3 col-sm-3">
                          Buscar:
                        </label>
                        <div class="col-xs-9 col-sm-9">
                          <input class="form-control recibidos" id="nombre" name="nombre" type="text" oninput="mensajesRecibidos('.recibidos');" />
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class="text-center" id="pieRecibidos">
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="div_tres">

          <!-- CONTENIDO APUNTES -->
          <div id="contenido_apuntes">
            <div class="panel panel-info">

              <div class="panel-heading">
                <h3 class="panel-title">
                  Sube tus apuntes
                </h3>
              </div>

              <div class="panel-body">
                <!-- PROBANDO FORMULARIOS -->

                <div class="form-group form-inline">
                  <label for="grado" class="control-label">Grado</label>
                  <div class="pull-right">
                    <select class="form-control buscar" onchange="verAsignaturas('.buscar');" id="grado" name="grado" pattern="(?=.*[A-Z]).{30,}" required>
                      <option value="GRADO EN CIENCIAS MATEMÁTICAS">Grado en Ciencias Matemáicas</option>
                      <option value="GRADO EN INGENIERÍA MATEMÁTICA">Grado en Ingeniería Matemática</option>
                    </select>
                  </div>
                </div>

                <!-- PEDIR EN REGISTRO EL CURSO TAMBIÉN! Y ELIMINA EL CAMPO APELLIDOS (NOBRE COMPLETO) -->
                <div class="form-group form-inline">
                  <label for="curso" class="control-label">Curso</label>
                  <div class="pull-right">
                    <select class="form-control buscar" onchange="verAsignaturas('.buscar');" id="curso" name="curso" pattern="(?=.*[A-Z]).{7,}" required>
                      <option value="PRIMERO">1</option>
                      <option value="SEGUNDO">2</option>
                      <option value="TERCERO">3</option>
                      <option value="CIENCIAS DE LA COMPUTACIÓN">CIENCIAS DE LA COMPUTACIÓN</option>
                      <option value="MATEMÁTICA PURA Y APLICADA I">MATEMÁTICA PURA Y APLICADA I</option>
                      <option value="MATEMÁTICA PURA Y APLICADA II">MATEMÁTICA PURA Y APLICADA II</option>
                      <option value="ECONOMATEMÁTICA">ECONOMATEMÁTICA</option>
                      <option value="TECNOMATEMÁTICA">TECNOMATEMÁTICA</option>
                      <option value="GEODESIA">GEODESIA</option>
                    </select>
                  </div>
                </div>

                <div class="form-group form-inline">
                  <label for="asignatura" class="control-label">
                    Nombre:
                  </label>
                  <div class="pull-right">
                    <input class="form-control buscar" oninput="verAsignaturas('.buscar');" id="asignatura" type="text" name="asignatura" />
                  </div>
                </div>

                <!-- ACABÓ PRUEBAS -->
              </div>

              <div class="panel-footer" id="pieBuscar">
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <script src="../js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/2.7.2/less.min.js"></script>
    <script src="../js/main.js" charset="utf-8"></script>
    <script src="../js/moment.min.js" charset="utf-8"></script>
    <!-- <script src="../js/progressbar.js" charset="utf-8"></script> -->
    <noscript>Necesitas javascript</noscript>
  </body>
</html>
