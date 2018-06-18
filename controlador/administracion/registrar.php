<?php
  if (!isset($_POST['rellenado'])) {
    header('location:../../vista/administracion/index.php');
    die('No entra desde ../../vista/registro/registro.php');
  };

  require '../../modelo/admin_consulta.php';
  $consulta = new Consulta;
  $existe = $consulta->existe('administracion','email');
  echo $existe;

	if ($existe) {
		echo "email_registrado";
  } else {
		$usuario = $consulta->registrar();
		session_start();
    $nombreCompleto = $usuario['nombre'] . ' ' . $usuario['apellidos'];
    echo $nombreCompleto .  " ha quedado registrado";

    $_SESSION["administrador"] = $nombreCompleto;
    header("location:../../vista/administracion/administrador.php");
  };

  $consulta->cerrar_conexion();
?>
