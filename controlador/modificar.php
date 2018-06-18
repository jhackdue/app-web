<?php
if (!isset($_POST['email']) && !isset($_POST['tabla'])) {
  header('location:../vista/index.php');
  die('No entra desde ../vista/recuperar/modificar.php');
}
require '../modelo/consulta.php';
$consulta = new Consulta;
$tabla = $consulta->getTabla($_POST['tabla']);
$emails = $consulta->buscar($tabla, array(), 'email', true);
foreach ($emails as $index => $registro) {
  if (password_verify( $registro['email'], $_POST['email'])) {
    $_POST['email'] = $registro['email'];
  }
}

$set = array('password', 'conectado');
$where = array('email');
$valores = array('conectado' => 1);
$usuario = $consulta->actualizar($tabla, $set, $where, $valores);

$email = password_hash($usuario['email'], PASSWORD_DEFAULT, array('cost' =>15));
$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
setcookie('email', $email, 0, '/~jhack/tfg/', $domain, false);

session_start();

if ($tabla == 'estudiantes') {
  $_SESSION["conectado"] = $usuario['conectado'];
} elseif ($tabla == 'profesores') {
  $_SESSION["online"] = $usuario['conectado'];
};

echo ($tabla == 'estudiantes')? "estudiante_modificado": "profesor_modificado";
$consulta->cerrar_conexion();

?>
