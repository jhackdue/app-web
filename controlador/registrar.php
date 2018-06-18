<?php
if (!isset($_POST['tabla'])) {
  header('location:../vista/index.php');
  die('No entra desde ../vista/registro/registro.php');
};
require '../modelo/consulta.php';
$consulta = new Consulta;
$tabla = $_POST['tabla'];
$where = array('email');
$existe = $consulta->existe($tabla, $where);

if ($existe) {
	echo "existe";
} else {
  switch ($tabla) {
    case 'estudiantes':
      $campos = array('nombre','nacimiento','email','password','conectado','grado','curso');
      $usuario = $consulta->registrar($tabla, $campos, array('conectado' => 1));
      $tablaHash = password_hash($tabla, PASSWORD_DEFAULT, array('cost'=>15));
      $email = password_hash($usuario['email'], PASSWORD_DEFAULT, array('cost' =>15));
      $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
      setcookie('email', $email, 0, '/~jhack/tfg/', $domain, false);
      setcookie('tabla', $tablaHash, 0, '/~jhack/tfg/', $domain, false);

      session_start();
      $_SESSION["conectado"] = $usuario['conectado'];
      echo "registrado";
      break;

    case 'profesores':
      $exito = $consulta->existe('profesoresAlta', array('codigoAlta','email'));
      if ($exito) {
        $campos = array('nombre','departamento', 'grado','email','password','conectado');
        $valores = array('conectado' => 1);
        $usuario = $consulta->registrar($tabla, $campos, $valores);
        $email = password_hash($usuario['email'], PASSWORD_DEFAULT, array('cost' =>15));
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('email', $email, 0, '/~jhack/tfg/', $domain, false);
        // setcookie('email', $email, 0, '/tfg/');

        session_start();
        $_SESSION["online"] = $usuario['conectado'];
        echo "registrado";
      }else {
        echo 'error_codigoAlta';
      };

      break;
    default:
      # code...
      break;
  }

};
$consulta->cerrar_conexion();

?>
