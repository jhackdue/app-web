<?php
if (!isset($_POST['tabla'])) {
  header('location:../vista/index.php');
  die('No entra desde ../vista/index.php');
};

require '../modelo/consulta.php';
$consulta = new Consulta;
$tabla = $consulta->getTabla($_POST['tabla']);
$where = array('email'); //sólo se pueden conectar utilizando su email;
$existe = $consulta->existe($tabla, $where);

if($existe){
  $usuario = $consulta->buscar($tabla, $where);
  $pass_usuario = $usuario['password'];
  $coincide = $consulta->verifica_pass($pass_usuario);

  if ($coincide){
    //actualizo Conectado a 1;
    $set = array('conectado');
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
    echo (!empty($_SESSION))? "cierto" : 'error';

  } else {
    echo "falso_c";
  };

} else {
  echo "falso_e";
};

$consulta->cerrar_conexion();

?>
