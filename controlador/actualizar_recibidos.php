<?php
if (empty($_FILES) && empty($_POST)) {
  header('location:../vista/index.php');
  die('No viene de un script');
};

require '../modelo/consulta.php';
$consulta = new Consulta;
$usuario = $consulta->usuarioConectado();
$accion = $_POST['accion'];
switch ($accion) {
  case 'marcarLeido':
    $fila = $_POST['filaVista'];


    $infoFila = $consulta->actualizar('chat', array('leido'), array('id'), array('leido'=>1, 'id'=>$fila));

    echo (empty($infoFila))?'false':'true';

    break;

  case 'responder':
    if (is_uploaded_file($_FILES['fichero']["tmp_name"])) {
      $nombre_fichero = $_FILES['fichero']["name"];
      $tipo_fichero = $_FILES['fichero']["type"];
      $tamagno_fichero = $_FILES['fichero']["size"];

      $carpeta_destino = "/home/jhack/public_html/tfg/vista/chat/";
      $exito = move_uploaded_file($_FILES['fichero']["tmp_name"], $carpeta_destino.$nombre_fichero);
      if ($exito){
        $filaResponder = $consulta->buscar('chat', array('id'), '*', false, array('id'=>$_POST['destino']));

        $campos = array('emisor','perfilEmisor','receptor','perfilReceptor','asunto',
                        'mensaje','fichero','leido','origen', 'respuesta', 'respuestaId');
        $valores = array();
        $valores['emisor'] = $usuario['email'];
        $valores['perfilEmisor'] = $consulta->getTabla($_COOKIE['tabla']);
        $valores['receptor'] = $filaResponder['emisor'];
        $valores['perfilReceptor'] = $filaResponder['perfilEmisor'];
        $valores['asunto'] = $filaResponder['asunto'];
        $valores['fichero'] = $nombre_fichero;
        $valores['leido'] = 0;
        $valores['origen'] = 'nuevo';
        $valores['respuesta'] = 1;
        $valores['respuestaId'] = $_POST['destino'];
        $insertado = $consulta->registrar('chat', $campos, $valores);
        echo (!empty($insertado))?json_encode($insertado):"error_mandar";
      }else {
        echo "error_mandar";
      };
    }else{
      $filaResponder = $consulta->buscar('chat', array('id'), '*', false, array('id'=>$_POST['destino']));

      $campos = array('emisor','perfilEmisor','receptor','perfilReceptor','asunto',
                      'mensaje','leido','origen', 'respuesta', 'respuestaId');
      $valores = array();
      $valores['emisor'] = $usuario['email'];
      $valores['perfilEmisor'] = $consulta->getTabla($_COOKIE['tabla']);
      $valores['receptor'] = $filaResponder['emisor'];
      $valores['perfilReceptor'] = $filaResponder['perfilEmisor'];
      $valores['asunto'] = $filaResponder['asunto'];
      $valores['leido'] = 0;
      $valores['origen'] = 'nuevo';
      $valores['respuesta'] = 1;
      $valores['respuestaId'] = $_POST['destino'];
      $insertado = $consulta->registrar('chat', $campos, $valores);
      echo (!empty($insertado))?json_encode($insertado):"error_mandar";
    };
    break;

  default:
    break;
};

$consulta->cerrar_conexion();
?>
