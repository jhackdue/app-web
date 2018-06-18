<!DOCTYPE html>
<html lang="es">
  <head>
    <?php
        session_start();
        if (isset($_SESSION["online"])) {
            header("location:principal.php");
            die('Ya hay una sesión creada');
        }
    ?>
    <meta charset="utf-8">
    <meta name="description" content="Aplicacion web para subir o descargar apuntes de la Facultad de Matemáticas">
    <meta name="author" content="Jhack Nicole Dueñas Povis">
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/logo.png">
    <title>App-Web - Profesores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
     <link href="https://fonts.googleapis.com/css?family=Roboto:500i" rel="stylesheet">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/profesores/index.css">
  </head>
  <body>

    <div class="container">
    	<div class="row">
  			<div class="col-md-6 col-md-offset-3">
  				<div class="panel panel-login">
  					<div class="panel-heading">
  						<div class="row">
  							<div class="col-xs-6">
  								<a class="active" id="login-form-link">Entrar</a>
  							</div>
  							<div class="col-xs-6">
  								<a id="register-form-link">Registrar</a>
  							</div>
  						</div>
  						<hr>
  					</div>
  					<div class="panel-body">
  						<div class="row">
  							<div class="col-lg-12">

  								<form id="login-form"  method="post" role="form" style="display: block;">
  									<div class="form-group">
  										<input type="text" name="email" id="email" tabindex="1" class="form-control" placeholder="Email" required autocomplete="off">
  									</div>
  									<div class="form-group">
  										<input type="password" name="password" id="password" tabindex="2" class="form-control" data-toggle="tooltip" data-placement="right" title="Recuerda que son más de 8 caracteres y al menos una mayúscula y un número" placeholder="Contraseña" required autocomplete="off">
  									</div>

  									<div class="form-group">
  										<div class="row">
  											<div class="col-sm-6 col-sm-offset-3">
  												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="entrar">
                          <?php
                            $tabla = password_hash('profesores', PASSWORD_DEFAULT, array('cost' =>15));
                          ?>
                          <input type="hidden" name="tabla" value="<?php echo $tabla; ?>">
  											</div>
  										</div>
  									</div>
  									<div class="form-group">
  										<div class="row">
  											<div class="col-lg-12">
  												<div class="text-center forgot-pass-content">
  													<a tabindex="5" class="forgot-pass">¿Olvidaste la contraseña?</a>
  												</div>

  											</div>

  										</div>
  									</div>

  								</form>
                  <div class="pr-wrap">
                      <div class="pass-reset">
                        <form id="forget-form"  method="post" role="form">
                          <label for="email">Introduce el email con el que te registraste</label>
                          <input type="email" name="email" id="email" placeholder="Email" required autocomplete="off" />
                          <?php
                          $tabla = password_hash('profesores', PASSWORD_DEFAULT, array('cost' =>15));
                          ?>
                          <input type="hidden" name="tabla" value=<?php echo $tabla; ?>>
                          <input type="submit" value="Submit" class="pass-reset-submit btn btn-success btn-sm" />
                        </form>
                      </div>
                  </div>
  								<form id="register-form" style="display: none;">
                    <div class="form-group">
  										<input type="text" name="codigoAlta" id="codigogAlta" tabindex="1" class="form-control" placeholder="CÓDIGO DE ALTA" required autocomplete="off">
  									</div>
  									<div class="form-group">
  										<input type="text" name="nombre" id="nombre" tabindex="2" class="form-control" placeholder="NOMBRE COMPLETO" required autocomplete="off">
  									</div>
                    <div class="form-group">
                      <select name="departamento" name="departamento" id="departamento" tabindex="3" class="form-control" placeholder="DEPARTAMENTO" required autocomplete="off">
                        <option value="">--- SELECCIONA UN DEPARTAMENTO ---</option>
                        <option value='ÁLGEBRA'>ÁLGEBRA</option>
                        <option value='ANÁLISIS MATEMÁTICO'>ANÁLISIS MATEMÁTICO</option>
                        <option value='ESTADÍSTICA E INVESTIGACIÓN OPERATIVA'>ESTADÍSTICA E INVESTIGACIÓN OPERATIVA</option>
                        <option value="GEOMETRÍA Y TOPOLOGÍA">GEOMETRÍA Y TOPOLOGÍA</option>
                        <option value="MATEMÁTICA APLICADA">MATEMÁTICA APLICADA</option>
                        <option value="ASTRONOMÍA Y GEODESIA">ASTRONOMÍA Y GEODESIA</option>
                        <option value="SISTEMAS INFORMÁTICOS Y COMPUTACIÓN">SISTEMAS INFORMÁTICOS Y COMPUTACIÓN</option>
                      </select>
  									</div>
                    <div class="form-group">
                      <select name="grado" id="grado" tabindex="4" class="form-control" placeholder="GRADO" required autocomplete="off">
                        <option value="">--- SELECCIONA UN GRADO POR DEFECTO ---</option>
                        <option value='GRADO EN CIENCIAS MATEMÁTICAS'>GRADO EN CIENCIAS MATEMÁTICAS</option>
                        <option value='GRADO EN INGENIERÍA MATEMÁTICA'>GRADO EN INGENIERÍA MATEMÁTICA</option>
                      </select>
  									</div>
  									<div class="form-group">
  										<input type="email" name="email" id="email" tabindex="5" class="form-control" placeholder="EMAIL" required autocomplete="off">
  									</div>
  									<div class="form-group">
  										<input type="password" name="password" id="password" tabindex="6" class="form-control" data-toggle="tooltip" data-placement="right" title="Recuerda que son más de 8 caracteres y al menos una mayúscula y un número" onblur="return ocultar(this.id);" onfocus="return ver(this.id);" placeholder="CONTRASEÑA" required autocomplete="off">

  									</div>
  									<div class="form-group">
  										<input type="password" name="confirm-password" id="confirm-password" tabindex="7" class="form-control" placeholder="CONFIRMAR CONTRASEÑA" onblur="return ocultar(this.id);" onfocus="return ver(this.id);" required autocomplete="off">
  									</div>
  									<div class="form-group">
  										<div class="row">
  											<div class="col-sm-6 col-sm-offset-3">
                          <input type="hidden" class="form-control" name="tabla" value="profesores">
  												<input type="submit" name="register-submit" id="register-submit" tabindex="8" class="form-control btn btn-register" value="registrar">
  											</div>
                      </div>
  									</div>
  								</form>
  							</div>
  						</div>
  					</div>
  				</div>
  			</div>
      </div>
    </div>

    <script src="../js/jquery.min.js" charset="utf-8"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="../js/profesores/inicio.js" charset="utf-8"></script>
    <noscript>Tu navegador solicita acceso a Javascript</noscript>
  </body>
</html>
