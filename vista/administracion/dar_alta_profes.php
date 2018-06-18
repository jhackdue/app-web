<!DOCTYPE html>
<html lang="es">
<head>
  <title>Registrar Profesores</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<?php
require '../../modelo/consulta.php';
$consulta = new Consulta();
$codigoNuevo = generarCodigos(10,20);
$mostrarCodigo = true;
$mostrarError = false;
$exito=false;
if (!empty($_POST) && $_POST['codigoAlta']!='') {
  //Registramos
  $existe = $consulta->existe('profesoresAlta', array('email'));
  $existe = ($existe) ? true : $consulta->existe('profesoresAlta', array('codigoAlta'));
  if (!$existe) {
    $profesor = $consulta->registrar('profesoresAlta', array('nombre','email','codigoAlta'));
    $mostrarCodigo = false;
    $exito=true;
  }else {
    $mostrarError = true;
  }
}

?>
<body>
  <div class="container">
    <a href="index.php"><h5>VOLVER</h5></a>
    <h1 class="text-center">REGISTRA PROFESORES</h1>
    <?php if ($mostrarCodigo): ?>
    <div class="row">
      <div class="col-sm-12 text-center">
        <h2>Código Generado: <?php echo $codigoNuevo; ?></h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input autocomplete="off" type="text" class="form-control" id="nombre" placeholder="Ingresa un nombre" name="nombre">
          </div>
          <div class="form-group">
            <label for="email">Email:</label>
            <input autocomplete="off" type="email" class="form-control" id="email" placeholder="Enter email" name="email">
          </div>
          <div class="form-group">
            <label for="codigoAlta">Código Alta:</label>
            <input autocomplete="off" type="text" class="form-control" id="codigoAlta" placeholder="Ingresa el código de alta" name="codigoAlta">
          </div>
          <button type="submit" class="btn btn-default">Dar alta</button>
        </form>

      </div>
    </div>
  <?php endif; ?>

  </div>

  <?php if ($exito) {?>
    <div class="row">
      <div class="col-sm-12">
        <h1><?php echo $profesor['nombre'] . " ha sido dado de alta."; ?></h1>
      </div>

    </div>
  <?php }elseif ($mostrarError) {?>
    <div class="row">
      <div class="col-sm-12">
        <h1>Probablemente ya exista ese código o el email está ya registrado. ¡Prueba a recuperar tu cuenta!</h1>
        <h2>O sino prueba poner otro Email</h2>
      </div>

    </div>
  <?php };?>
  <?php $consulta->cerrar_conexion(); ?>
</body>
</html>
