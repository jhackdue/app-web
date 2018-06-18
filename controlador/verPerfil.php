<?php
if (!isset($_POST['perfil']) && !isset($_POST['usuario'])) {
  header('location:../vista/index.php');
  die('No entra desde algún script');
};

require '../modelo/consulta.php';
$consulta = new Consulta;

$usuario = $consulta->buscar($_POST['perfil'], array('id'), '*', false, array('id'=>$_POST['usuario']));
$usuario['perfil'] = ($_POST['perfil'] == 'estudiantes') ? 'Estudiante' : 'Profesor(a)';

echo json_encode($usuario);
$consulta->cerrar_conexion();

?>
