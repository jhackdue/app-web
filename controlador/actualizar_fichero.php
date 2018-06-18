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
  case 'subir':
    $asignatura = $_POST['asignatura'];
    $indexFile = str_replace(" ","_", $asignatura);
    $nombre_fichero = $_FILES[$indexFile]["name"];

    $tabla = $_POST['tabla'];
    $existe = $consulta->existe($tabla, array('fichero','asignatura'), array('fichero' => $nombre_fichero));
    if ($existe) {
      echo "error_existe";
    } else {
      $tipo_fichero = $_FILES[$indexFile]["type"];
      $tamagno_fichero = $_FILES[$indexFile]["size"];
      // if ($tamagno_fichero <= 1048576) { //AQUÍ TIENES QUE PROBAR LA CAPACIDAD DEL SERVIDOR
      if ($tamagno_fichero <= 2097152){
        if (strpos($tipo_fichero, 'image/') !== false ||
            strpos($tipo_fichero, 'application/') !== false ||
            strpos($tipo_fichero, 'video/') !== false ||
            strpos($tipo_fichero, 'text/') !== false) {

          if (is_uploaded_file($_FILES[$indexFile]["tmp_name"])) {
            $perfilFichero = $_POST['perfilFichero'];
            $carpeta_destino = "/home/jhack/public_html/tfg/vista/ficheros/" . $perfilFichero . "/" . $asignatura . "/";
            //MOVEMOS LA IMAGEN DEL DIRECTORIO TEMPORAL AL DIRECTORIO ESCOGIDO
            $exito = move_uploaded_file($_FILES[$indexFile]["tmp_name"], $carpeta_destino.$nombre_fichero);
            if ($exito){
              $titulo = $_POST['titulo'];

              $campos = array('email','perfil','accion','fichero','asignatura',
                              'grado','fuente','nombreFichero','descripcion',
                              'perfilFichero','tipo','origen');
              $valores = array();
              $valores['email'] = $usuario['email'];
              $valores['perfil'] = $consulta->getTabla($_COOKIE['tabla']);
              $valores['fichero'] = $nombre_fichero;
              $asignatura = $consulta->buscar('asignaturas',array('asignatura'));
              $valores['grado'] = $asignatura['grado'];
              $valores['nombreFichero'] = $titulo;
              $valores['tipo'] = $tipo_fichero;
              $valores['origen'] = $usuario['email'];
              $insertado = $consulta->registrar($tabla, $campos, $valores);
              echo json_encode($insertado);
            }else {
              echo "error_deTemporal_aCarpeta";
            };
          }else {
            echo "no_enTemporal";
          };
        } else {
          echo (is_uploaded_file($_FILES[$indexFile]["tmp_name"]))? 'error_formato' : 'error_upload';
        };
      }else{
        echo "error_tamaño";
      };
    };

    break;

  case 'comentar':
    $comentador = $usuario;
    $comentario = $_POST['comentario'];
    $tabla = $_POST['tabla'];
    $fila = $_POST['origen'];

    $autor = $consulta->buscar($tabla, array('id'), '*', false, array('id' => $fila));

    $campos = array('email','perfil','accion','fichero','asignatura','grado','fuente','nombreFichero','descripcion','perfilFichero','tipo','origen');
    $valores = $autor;
    $valores['email'] = $comentador['email'];
    $valores['perfil'] = $consulta->getTabla($_COOKIE['tabla']);
    $valores['descripcion'] = $comentario;
    $valores['origen'] = $autor['id'];
    $insertado = $consulta->registrar($tabla, $campos, $valores);
    $exito = (empty($insertado) ? false : true);
    echo ($exito) ? json_encode($insertado) : 'error_comentario';

    break;

  case 'valorar':

    $valorador = $usuario;
    $tabla = $_POST['tabla'];
    $fila = $_POST['fila'];
    $valor = $_POST['valor'];
    $elemento = $_POST['valorado'];

    if ($elemento == 'fichero') {
      $where = array('accion','descripcion','origen');
      $ValoresWhere = array('descripcion'=>$elemento,'origen'=>$fila);
      $Valoraciones = $consulta->buscar($tabla, $where, '*', true, $ValoresWhere);
    } elseif ($elemento == 'usuario') {
      $tablaValorado = $_POST['tablaValorado'];
      $where = array('perfil','accion','descripcion','origen');
      $ValoresWhere = array('perfil'=>$tablaValorado,'descripcion'=>$elemento,'origen'=>$fila);
      $Valoraciones = $consulta->buscar($tabla, $where, '*', true, $ValoresWhere);
    };

    $existe = false;
    foreach ($Valoraciones as $index => $row) {
      if ($valorador['email'] == $row['email']) {
        $existe = true;
      }
    };

    if ($existe) {
      echo 'valorado';
    } elseif ($valor == '') {
      echo "no_hay_valor";
    } else {
      if ($elemento == 'fichero') {
        $autor = $consulta->buscar($tabla, array('id'),'*',false,array('id'=>$fila));
        $campos = array('email','perfil','accion','fichero','asignatura','grado','fuente','nombreFichero','descripcion','perfilFichero','valoracion','tipo','origen');
        $valores = $autor;
        $valores['email'] = $valorador['email'];
        $valores['perfil'] = $consulta->getTabla($_COOKIE['tabla']);
        $valores['descripcion'] = $elemento;
        $valores['valoracion'] = $valor;
        $valores['origen'] = $fila;
        $insertado = $consulta->registrar($tabla, $campos, $valores);

        //Actualizar el campo valoracion

        $Valoraciones = $consulta->buscar($tabla, $where, '*', true, $ValoresWhere);
        $veces = 1 + count($Valoraciones);
        $valorActual = $autor['valoracion'];
        foreach ($Valoraciones as $index => $row) {
          $valorActual += $row['valoracion'];
        }
        $valorActual = ceil($valorActual / $veces);
        $filaActualizada = $consulta->actualizar($tabla, array('valoracion'), array('id'), array('valoracion' => $valorActual, 'id'=>$fila));
        $exito = (empty($filaActualizada) ? false : true);
        echo ($exito) ? json_encode($filaActualizada) : 'error_valorado';

      }elseif ($elemento == 'usuario') {

        $autor = $consulta->buscar($tablaValorado, array('id'),'*',false,array('id'=>$fila));
        $campos = array('email','perfil','accion','fichero','asignatura','fuente','nombreFichero','descripcion','perfilFichero','valoracion','tipo','origen');
        $valores = array();
        $valores['email'] = $valorador['email'];
        $valores['perfil'] =  $tablaValorado;//EN PERFIL GUARDAMOS LA TABLA DEL VALORADO
        $valores['descripcion'] = $elemento;
        $valores['valoracion'] = $valor;
        $valores['origen'] = $fila;
        $insertado = $consulta->registrar($tabla, $campos, $valores);

        //Actualizar el campo valoracion

        $Valoraciones = $consulta->buscar($tabla, $where, '*', true, $ValoresWhere);
        $veces = 1 + count($Valoraciones);
        $valorActual = $autor['valoracion'];
        foreach ($Valoraciones as $index => $row) {
          $valorActual += $row['valoracion'];
        }
        $valorActual = ceil($valorActual / $veces);
        $filaActualizada = $consulta->actualizar($tablaValorado, array('valoracion'), array('id'), array('valoracion' => $valorActual, 'id'=>$fila));
        $exito = (empty($filaActualizada) ? false : true);
        echo ($exito) ? json_encode($filaActualizada) : 'error_valorado';
      } else {
        echo "no_elemento";
      };

    };

    break;

  case 'descargar':
    $fila = $_POST['fila'];
    $filaDescargada = $consulta->buscar('gestionFicheros', array('id'), '*', false, array('id'=>$fila));


    $campos = array('email','perfil','accion','fichero','asignatura', 'grado','fuente','nombreFichero','descripcion','perfilFichero','tipo','origen');
    $valores = $filaDescargada;
    $valores['email'] = $usuario['email'];
    $valores['perfil'] = $consulta->getTabla($_COOKIE['tabla']);
    $valores['origen'] = $fila;
    $insertado = $consulta->registrar('gestionFicheros', $campos, $valores);

    $descargado = $filaDescargada['descargado'];
    $descargado += 1;
    $filaActualizada = $consulta->actualizar('gestionFicheros',
    array('descargado'), array('id'), array('id'=>$fila, 'descargado'=> $descargado));

    echo (!empty($insertado))?json_encode($insertado):'error';
    break;

  case 'contactar':
    if (is_uploaded_file($_FILES['fichero']["tmp_name"])) {
      $nombre_fichero = $_FILES['fichero']["name"];
      $tipo_fichero = $_FILES['fichero']["type"];
      $tamagno_fichero = $_FILES['fichero']["size"];

      $receptor = $consulta->buscar($_POST['perfilReceptor'], array('id'), '*', false, array('id'=>$_POST['destino']));
      $carpeta_destino = "/home/jhack/public_html/tfg/vista/chat/";
      $exito = move_uploaded_file($_FILES['fichero']["tmp_name"], $carpeta_destino.$nombre_fichero);
      if ($exito){
        $campos = array('emisor','perfilEmisor','receptor','perfilReceptor','asunto',
                        'mensaje','fichero','origen');
        $valores = array();
        $valores['emisor'] = $usuario['email'];
        $valores['perfilEmisor'] = $consulta->getTabla($_COOKIE['tabla']);
        $valores['receptor'] = $receptor['email'];
        $valores['fichero'] = $nombre_fichero;
        $valores['origen'] = 'nuevo';
        $insertado = $consulta->registrar('chat', $campos, $valores);
        echo json_encode($insertado);
      }else {
        echo "error_mandar";
      };
    }else{
      $receptor = $consulta->buscar($_POST['perfilReceptor'], array('id'), '*', false, array('id'=>$_POST['destino']));

      $campos = array('emisor','perfilEmisor','receptor','perfilReceptor','asunto',
            'mensaje','origen');
      $valores = array();
      $valores['emisor'] = $usuario['email'];
      $valores['perfilEmisor'] = $consulta->getTabla($_COOKIE['tabla']);
      $valores['receptor'] = $receptor['email'];
      $valores['origen'] = 'nuevo';
      $insertado = $consulta->registrar('chat', $campos, $valores);
      echo json_encode($insertado);
    };
    break;

  default:
    break;
};

$consulta->cerrar_conexion();
?>
