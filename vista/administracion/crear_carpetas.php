<?php
require '../../modelo/consulta.php';
$consulta = new Consulta;

$asignaturas = $consulta->buscar('asignaturas', array(), 'asignatura', true);
$carpeta_destino = $_SERVER['DOCUMENT_ROOT'] . "/tfg/vista/ficheros/";
$perfilFichero = array('examen','teoría','práctica');
$exito = false;
foreach ($perfilFichero as $index => $carpeta) {
  $destino = $carpeta_destino . $carpeta . "/";
  foreach ($asignaturas as $i => $arrayAsignaturas) {
    $futura_carpeta = $destino . $arrayAsignaturas['asignatura'];
    if (!is_dir($futura_carpeta)) {
      $exito = mkdir($futura_carpeta, 0777);
    };
  }
}

$consulta->cerrar_conexion();
echo $exito;
?>
