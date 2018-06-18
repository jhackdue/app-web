<?php
if (!isset($_POST['ajax']) ) {
  header('location:../vista/index.php');
  die('No entra desde algún script');
};

require '../modelo/consulta.php';
$consulta = new Consulta;
$usuario = $consulta->usuarioConectado();
$tabla = $consulta->getTabla($_COOKIE['tabla']);
$mensajes = $consulta->buscar('chat',
                              array('receptor',
                                    'perfilReceptor',
                                    'leido'),
                              '*',
                              true,
                              array('receptor' => $usuario['email'],
                                    'perfilReceptor' => $tabla,
                                    'leido' => 0));
$nuevos = count($mensajes);
if ($nuevos>0) {
  echo json_encode(array('mensajes'=>$nuevos));
}else {
  echo "no_existe";
};

$consulta->cerrar_conexion();
?>
