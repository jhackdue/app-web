<?php
if (empty($_FILES) && empty($_POST)) {
  header('location:../vista/index.php');
  die('No viene de un script');
};

require '../modelo/consulta.php';
$consulta = new Consulta;
$arrayElegido = (empty($_FILES)) ? $_POST : $_FILES ;

foreach ($arrayElegido as $campo => $valor):

  if (!empty($_FILES)) {
    $nombre_imagen= $_FILES[$campo]["name"];
    $tipo_imagen= $_FILES[$campo]["type"];
    $tamagno_imagen= $_FILES[$campo]["size"];
    $exito = (is_uploaded_file($_FILES[$campo]["tmp_name"]));
    if ($tamagno_imagen<=2097152) { //Mi servidor Localhost sólo puedo subir imágenes hasta 1MB = 1048576 Bytes
      if (strpos($tipo_imagen, 'image/') !== false ) {
        //RUTA DE LA CARPETA DESTINO EN EL SERVIDOR
        $carpeta_destino = "/home/jhack/public_html/tfg/vista/";
        if ($exito) {
          //MOVEMOS LA IMAGEN DEL DIRECTORIO TEMPORAL AL DIRECTORIO ESCOGIDO
          switch ($campo) {
            case 'background':
              $carpeta_destino.= 'img/backgrounds/';
              break;

            case 'foto':
              $carpeta_destino.= 'img/perfiles/';
              break;

            default:
              break;
          }
          move_uploaded_file($_FILES[$campo]["tmp_name"], $carpeta_destino.$nombre_imagen);
          $dato = $nombre_imagen;

        } else {
          die('El tamaño de la imagen supera los 2MB');
        };
      } else {
        die(($exito) ? 'Sólo se aceptan imágenes' : 'El tamaño de la imagen supera los 2MB');
      };
    }else{
      echo "El tamaño de la imagen supera los 2MB";
    };
  }else {
    $dato = $valor;
  };

  $conectado = $consulta->usuarioConectado();
  $tabla = $consulta->getTabla($_COOKIE['tabla']);
  $set = array($campo);
  //Revisa si es probable escribir set y valores en un mismo array;
  $where = array('email');
  $valores = array($campo=> $dato, 'email'=>$conectado['email']);
  $usuario = $consulta->actualizar($tabla, $set, $where, $valores);

  echo json_encode($usuario);
endforeach;


$consulta->cerrar_conexion();
?>
