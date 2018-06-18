<?php
// porque tenemos que indicarle la sesión que queremos
// en el navegador actual. Es decir, reanuda las sesiones.
session_start();
if (!isset($_SESSION)){
  header("location:../vista/index.php");
  die('No hay sesión creada');
};

require '../modelo/consulta.php';
$consulta = new Consulta;
$tabla = $consulta->getTabla($_COOKIE['tabla']);
if ($tabla == 'estudiantes' && !isset($_SESSION["conectado"])) {
  header("location:../vista/index.php");
  die('No hay sesión creada');
}elseif ($tabla == 'profesores' && !isset($_SESSION["online"])){
  header("location:../vista/profesores/index.php");
  die('No hay sesión creada');
}
//-----------------
$emails = $consulta->buscar($tabla, array('conectado'), 'email', true, array('conectado' => 1));
foreach ($emails as $index => $registro) {
  if (password_verify($registro['email'], $_COOKIE['email'])) {
    $email = $registro['email'];
  }
}

//actualizo Conectado a 0;
$set = array('conectado');
//Revisa si es probable escribir set y valores en un mismo array;
$where = array('email');
$valores = array('conectado' => 0, 'email'=>$email);
$usuario = $consulta->actualizar($tabla, $set, $where, $valores);


$consulta->cerrar_conexion();
//------------------

if (!empty($_COOKIE)) {
  foreach ($_COOKIE as $nombre => $valor) {
    switch ($nombre) {
      case 'email':
      case 'acepta_cookies':
      case 'tabla':
        // setcookie($nombre, $valor, time()-1,($_SERVER['HTTP_HOST'] . '~jhack/tfg/'));
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie($nombre, $valor, time()-1, '/~jhack/tfg/', $domain, false);
        break;

      default:
        continue;
    }
  }
};

session_destroy();
//destruimos la sesion

if ($tabla == 'estudiantes') {
  header("location:../vista/index.php");
} elseif($tabla == 'profesores') {
  header("location:../vista/profesores/index.php");
}

?>
