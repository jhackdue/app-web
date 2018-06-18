<?php
if (!isset($_POST['tabla'])) {
  header('location:../vista/index.php');
  die('No entra desde ../vista/index.php');
}
require '../modelo/consulta.php';
$consulta = new Consulta;
$tabla = $consulta->getTabla($_POST['tabla']);
$where = array('email');
$existe = $consulta->existe($tabla, $where);

if ($existe) {

  $usuario = $consulta->buscar($tabla, $where);
  if (isset($_COOKIE['email'])) {
    echo "conectado";
  } else {
    $exito = $consulta->recuperar($usuario['email'], $usuario['nombre']);
    if ($exito) {
      $email = password_hash($usuario['email'], PASSWORD_DEFAULT, array('cost' =>15));
      $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
      setcookie('modificar', $email, time()+600, '/~jhack/tfg/', $domain, false); //lo creo por 10 minutos
      echo "enviado";
    } else {
      echo "error_enviar";
    };
  }


} else {
  echo "no_existe";
};

$consulta->cerrar_conexion();
?>
