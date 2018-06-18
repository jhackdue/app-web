<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/logo.png">
    <title>App-Web | Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>

    <meta name="description" content="Página de registro para la aplicación web sobre apuntes de matemáticas">
    <meta name="author" content="Jhack Nicole Dueñas Povis">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Pacifico" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap-datetimepicker.css">
    <link rel="stylesheet/less" type="text/css" href="../css/registro.less">
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6">
    			<div id="titulo">
            <a style="cursor:pointer;" onclick="Volver();"><img alt="Logo Inicio" src="../img/app/logo.png" id="logo" class="img-circle"></a>
    				<h1 >
    					¡Regístrate!
    				</h1>
    			</div>
    			<form role="form" id="formulario" action="../../controlador/registrar.php" method="post" >
            <div class="form-group has-feedback">
              <label for="formulario_nombre">Nombre Completo</label>
              <input type="text" name="nombre" class="form-control" id="formulario_nombre" placeholder="nombre" autocomplete="off" required>
              <span class="glyphicon glyphicon-remove form-control-feedback error" aria-hidden="true"></span>
            </div>

            <div class="form-group has-feedback">
              <label for="formulario_nacimiento">Fecha de Nacimiento</label>
              <div class="input-group date" id="datetimepicker1">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                <input type="date" name="nacimiento" class="form-control" value="" id="formulario_nacimiento" autofocus required>
              </div>
              <span class="glyphicon glyphicon-remove form-control-feedback error" aria-hidden="true"></span>
            </div>

            <div class="form-group has-feedback">
              <label for="formulario_email">Email</label>
              <input type="email" name="email" class="form-control" id="formulario_email" placeholder="email" autocomplete="off" aria-describedby="mensaje_email" required>
              <span class="glyphicon glyphicon-remove form-control-feedback error" aria-hidden="true"></span>
              <span id="mensaje_email">Este Usuario ya existe</span>
            </div>


            <div class="form-group has-feedback">
              <label for="formulario_password">Contraseña</label>
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></span>
                <input type="password" name="password" class="form-control" id="formulario_password" placeholder="contraseña" autocomplete="off" aria-describedby="ayuda_pass" required>
              </div>
              <span id='ayuda_pass' class="help-block">La contraseña debe tener al menos: 8 caracteres, una mayúscula y un número</span>
              <span class="glyphicon glyphicon-remove form-control-feedback error"></span>
            </div>

            <div class="form-group has-feedback">
              <label for="formulario_grado">Grado</label>
              <select class="form-control" id="formulario_grado" name="grado" pattern="(?=.*[A-Z]).{30,}" required>
                <option value="">--- selecciona un grado ---</option>
                <option value="GRADO EN CIENCIAS MATEMÁTICAS">Grado en Ciencias Matemáicas</option>
                <option value="GRADO EN INGENIERÍA MATEMÁTICA">Grado en Ingeniería Matemática</option>
              </select>
              <span class="glyphicon glyphicon-remove form-control-feedback error" aria-hidden="true"></span>
            </div>

            <div class="form-group has-feedback">
              <label for="formulario_curso">Curso</label>
              <select class="form-control" onclick="return mostrarCurso(this.id);" id="formulario_curso" name="curso" pattern="(?=.*[A-Z]).{7,}" required>
                <option value="">--- selecciona un grado ---</option>
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
              <span class="glyphicon glyphicon-remove form-control-feedback error" aria-hidden="true"></span>
            </div>

            <div id="formulario_boton">
              <input type="hidden" name="tabla" id="formulario_tabla" class="valid" value="estudiantes">

              <input type="submit" class="btn btn-default" name="enviar" id="enviar" value="Registrar">
            </div>

    			</form>
        </div>
      </div>
    </div>
    <script src="../js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/less.js/2.7.2/less.min.js"></script>
    <script src="../js/moment.min.js" charset="utf-8"></script>
    <script src="../js/bootstrap-datetimepicker.min.js" charset="utf-8"></script>
    <script src="../js/registro.js" charset="utf-8"></script>
    <noscript>Necesitas javascript</noscript>
  </body>
</html>
