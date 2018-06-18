<?php
if (!isset($_POST['enviado'])) {
  header('location:../../vista/administracion/index.php');
  die('No entra desde ../../vista/administracion/index.php');
};

require '../../modelo/consulta.php';
$consulta = new Consulta();
$where = array('email'); //sólo se pueden conectar utilizando su email;
$existe = $consulta->existe('administracion', $where);
if ($existe) {
  $usuario = $consulta->buscar('administracion',$where);
  $pass_usuario = $usuario['password'];
  $nombreCompleto = $usuario['nombre'] . ' ' . $usuario['apellidos'];
  $coincide = $consulta->verifica_pass($pass_usuario);

  if ($coincide){
    session_start();
    $_SESSION["administrador"] = $nombreCompleto;
    echo (!empty($_SESSION))? "cierto" : 'error';
  } else {
    echo "falso_c";
  };

} else {
  echo "falso_e";
};

$consulta->cerrar_conexion();

?>
