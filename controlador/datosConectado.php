<?php
if (!isset($_POST['ajax']) ) {
  header('location:../vista/index.php');
  die('No entra desde algún script');
};

require '../modelo/consulta.php';
$consulta = new Consulta;
$usuario = $consulta->usuarioConectado();
$consulta->cerrar_conexion();
echo json_encode($usuario);
?>
