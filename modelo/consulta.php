<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'conexion.php';

  class Consulta extends Conexion
  {
    public $ficheros;
    public function __construct()
    {
      $this->ficheros = array();
      parent::__construct();
    }

    public function total($tabla)
    {
      $sql ="SELECT * FROM " . $tabla ;
      $sentencia = $this->conexion_db->query($sql);
      $num_registros = $sentencia->rowCount();
      $sentencia->closeCursor();

      return $num_registros;
    }

    public function paginacion($tabla, $total_registros)
    {
      $mostrar = (isset($_GET['mostrar']) && $_GET['mostrar']!= '') ? $_GET['mostrar'] : 15;
      $comentarios =  (isset($_GET['comentarios']) && $_GET['comentarios']!= '') ? $_GET['comentarios'] : 2;
      $respuestas =  (isset($_GET['respuestas']) && $_GET['respuestas']!= '') ? $_GET['respuestas'] : 2;

      if (isset($_GET["pagina"]) && $_GET['pagina']!='') {
          if ($_GET["pagina"]<=1) {
              $pagina=1;
          }else {
              $pagina=$_GET["pagina"];
          }
      }else {
          $pagina=1;// página actual en la que empezamos el index
      };

      $empezar_desde = ($pagina-1)*$mostrar;
      $empezar_comentarios = 0;
      $empezar_respuestas = 0;
      $total_comentarios = ceil($total_registros / $comentarios);
      $total_respuestas = ceil($total_registros / $respuestas);
      $total_paginas = ceil($total_registros / $mostrar);

      return array('empezar'=> $empezar_desde,
                   'empezar_comentarios'=>$empezar_comentarios,
                   'empezar_respuestas'=>$empezar_respuestas,
                   'total'=>$total_paginas,
                   'total_comentarios'=>$total_comentarios,
                   'total_respuestas'=>$total_respuestas,
                   'mostrar'=>$mostrar,
                   'mostrar_comentarios'=>$comentarios,
                   'mostrar_respuestas'=>$respuestas,
                   'registros'=>$total_registros
                  );
    }


    public function get_resultadosPaginacion($tabla,$total,$accion,$id_fila='', $order='DESC')
    {
      $pag = $this->paginacion($tabla, $total);
      switch ($accion) {
        case 'subir':
          $grado = $_POST['grado'];
          $busqueda = $_POST['busqueda'];
          $empezar_desde = $pag['empezar'];
          $mostrar = $pag['mostrar'];
          $s = "SELECT * FROM gestionFicheros where (accion='subir') AND (grado like '%$grado%') AND ";
          $s .= "(CONCAT(email, fichero, asignatura, fuente, nombreFichero, descripcion, perfilFichero) ";
          $s .= "LIKE '%$busqueda%') ORDER BY valoracion DESC, fecha DESC LIMIT $empezar_desde, $mostrar";

          break;

        case 'verPerfil':
          $accion = $_POST['accion'];
          $persona = $this->buscar($_POST['perfil'], array('id'), '*', false, array('id'=>$_POST['usuario']));
          $email = $persona['email'];
          $perfil = $_POST['perfil'];
          $empezar_desde = $pag['empezar'];
          $mostrar = $pag['mostrar'];
          $s = "SELECT * FROM gestionFicheros where (email='$email') AND (perfil='$perfil') AND ";
          $s .= "(accion='$accion') ORDER BY valoracion DESC, fecha DESC LIMIT $empezar_desde, $mostrar";
          break;

        case 'comentar':
          $empezar_desde = $pag['empezar_comentarios'];
          $mostrar = $pag['mostrar_comentarios'];
          $s = "SELECT * FROM $tabla WHERE accion='$accion' AND origen='$id_fila' ORDER BY fecha $order LIMIT $empezar_desde, $mostrar";
          break;

        case 'responder':
          $empezar_desde = $pag['empezar_respuestas'];
          $mostrar = $pag['mostrar_respuestas'];
          $s = "SELECT * FROM $tabla WHERE origen='$id_fila' ORDER BY fecha $order LIMIT $empezar_desde, $mostrar";
          break;

        case 'usuarios':
          $empezar_desde = $pag['empezar'];
          $mostrar = $pag['mostrar'];
          $perfil = $_GET['user'];
          $s = "SELECT * FROM gestionFicheros WHERE accion='subir' and perfil='$perfil' ORDER BY valoracion $order, fecha $order LIMIT $empezar_desde, $mostrar";
          break;

        case 'inicio':
          $empezar_desde = $pag['empezar'];
          $mostrar = $pag['mostrar'];
          $grado = $_POST['grado'];
          $perfil = $_POST['perfil'];
          $perfilFichero = $_POST['perfilFichero'];
          $busqueda = $_POST['busqueda'];
          $s = "SELECT * FROM gestionFicheros WHERE ((perfil='$perfil') AND (accion='subir') AND ";
          $s .= "(grado='$grado') AND (perfilFichero='$perfilFichero')) AND ";
          $s .= "(CONCAT(email,fichero, asignatura, fuente, nombreFichero,descripcion) like '%$busqueda%') ORDER BY valoracion DESC, fecha DESC LIMIT $empezar_desde, $mostrar";
          break;

        default:
          break;
      }


      $consulta = $this->conexion_db->query($s);

      while ($registro=$consulta->fetch(PDO::FETCH_ASSOC)) {
          $this->ficheros[]=$registro; // almacenamos el registro en el array productos
      }
      $consulta->closeCursor();
      return $this->ficheros;
    }

    public function busqueda($arrayValores = array())
    {
      $tabla = (isset($arrayValores['tabla']))? $arrayValores['tabla'] : $_POST['tabla'];
      switch ($tabla) {
        case 'asignaturas':
          $grado = $_POST['grado'];
          $curso = $_POST['curso'];
          $asignatura = $_POST['asignatura'];
          $sql = "SELECT * FROM asignaturas WHERE (CONCAT(grado,curso) LIKE CONCAT('%$grado%','%$curso%')) AND (asignatura LIKE '%$asignatura%')";
          break;

        case 'ficheros':
          $accion = (isset($arrayValores['accion']))? $arrayValores['accion'] : $_POST['accion'];
          $busqueda = $arrayValores['busqueda'];
          $grado = (isset($arrayValores['grado']))? $arrayValores['grado'] : $_POST['grado'];
          $sql = "SELECT * FROM gestionFicheros where (accion='subir') AND (grado='%$grado%') and ";
          $sql .= "(CONCAT(email, fichero, asignatura, fuente, nombreFichero, descripcion, perfilFichero) ";
          $sql .= "LIKE '%$busqueda%') ORDER BY valoracion DESC, fecha DESC ";
          break;

        case 'profesores':
        case 'estudiantes':
          $conectado = $this->usuarioConectado();
          $nombreConectado = $conectado['nombre'];
          $emailConectado = $conectado['email'];
          $nombre = (isset($arrayValores['nombre']))? $arrayValores['nombre'] : $_POST['nombre'];
          $nombre = ($nombre=='')?$conectado['grado']:$nombre;
          $sql = "SELECT * FROM $tabla where ((nombre like '%$nombre%') OR (email like '%$nombre%') OR (grado like '%$nombre%') OR ";
          if ($tabla =='estudiantes') {
            $sql .= "(curso like '%$nombre%')) ";
          } else {
            $sql .= "(departamento like '%$nombre%'))";
          };
          $sql .= "AND (CONCAT(nombre,email) not like CONCAT('%$nombreConectado%','%$emailConectado%')) ORDER BY valoracion DESC";
          break;

        case 'chat':
          $conectado = $this->usuarioConectado();
          $receptor = $conectado['email'];
          $perfilReceptor = $this->getTabla($_COOKIE['tabla']);
          $perfilEmisor = $arrayValores['perfil'];
          $busqueda = $arrayValores['busqueda'];
          $sql = "SELECT * FROM chat WHERE ((receptor='$receptor') AND ";
          $sql .= "(perfilReceptor='$perfilReceptor') AND (origen='nuevo') AND ";
          $sql .= "(perfilEmisor='$perfilEmisor')) AND " .
          "((emisor like '%$busqueda%') OR (asunto like '%$busqueda%') OR ";
          $sql .= "(mensaje like '%$busqueda%') OR (fichero like '%$busqueda%') OR (fecha like '%$busqueda%')) ORDER BY fecha DESC";
          break;

        case 'inicio':
          $grado = $_POST['grado'];
          $perfil = $_POST['perfil'];
          $perfilFichero = $_POST['perfilFichero'];
          $busqueda = $_POST['busqueda'];
          $sql = "SELECT * FROM gestionFicheros WHERE ((perfil='$perfil') AND (accion='subir') AND ";
          $sql .= "(grado='$grado') AND (perfilFichero='$perfilFichero')) AND ";
          $sql .= "(CONCAT(email,fichero, asignatura,fuente, nombreFichero,descripcion) like '%$busqueda%') ORDER BY valoracion DESC, fecha DESC";
          break;

        default:
          break;
      }


      $sentencia = $this->conexion_db->query($sql);
      $resultado = $sentencia->fetchAll(PDO::FETCH_ASSOC);
      $sentencia->closeCursor();

      return $resultado;
    }

    public function getTabla($hashTabla)
    {
      if (password_verify('estudiantes', $hashTabla)) {
        return 'estudiantes';
      } elseif (password_verify('profesores', $hashTabla)) {
        return 'profesores';
      };

    }

    public function usuarioConectado()
    {
      $tabla = $this->getTabla($_COOKIE['tabla']);
      $emails = $this->buscar($tabla, array('conectado'), 'email', true, array('conectado' => 1));
      foreach ($emails as $index => $registro) :
        if (password_verify($registro['email'], $_COOKIE['email'])) {
          $usuario = $this->buscar($tabla, array('email'), '*', false, array('email' => $registro['email']));
        }
      endforeach;

      return $usuario;
    }

    public function existe($tabla, $arrayWhere, $arrayValores = array(), $logic = 'AND')
    {
      $usuario = $this->buscar($tabla, $arrayWhere,'*', false, $arrayValores, $logic);
      $existe = (empty($usuario)) ? false : true ;
      return $existe;
    }

    public function buscar($tabla, $arrayWhere, $busqueda = '*', $todo=false, $arrayValores= array(), $logic='AND', $orderBy = 'id', $orderDirec = 'ASC')
    {
      $where = '';
      $exec = array();

      foreach ($arrayWhere as $index => $campo){
        if ($campo != 'password') {
          $marcador = ":$campo";
          $where .= "$campo=$marcador $logic ";

          if (isset($_POST[$campo])) {
            $recibido = htmlentities(addslashes($_POST[$campo]));
          } elseif (isset($_GET[$campo])) {
            $recibido = htmlentities(addslashes($_GET[$campo]));
          } else{
            $recibido = htmlentities(addslashes($arrayValores[$campo]));
          };
          $recibido = html_entity_decode($recibido, ENT_QUOTES | ENT_HTML401, "UTF-8");
          $recibido = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $recibido)));

          $campo = strtolower($campo, 'UTF-8');

          switch ($campo) {
            case 'foto':
            case 'background':
            case 'fichero':
            case 'descripcion':
            case 'mensaje':
            case 'asunto':
              $valor = $recibido;
              break;

              case 'origen':
              case 'email':
              case 'emisor':
              case 'receptor':
              case 'perfilemisor':
              case 'perfilreceptor':
                $valor = mb_strtolower($recibido, 'UTF-8');
                break;

            case 'password':
            continue;

            default:
            $valor = mb_strtoupper($recibido, 'UTF-8');
            break;
          }

          $exec[$marcador] = $valor;
        };
      };

      $where = (rtrim($where,' AND '));
      if ($where != ''){
        $sql ="SELECT " . $busqueda . " FROM " . $tabla . " WHERE " . $where . " ORDER BY " . $orderBy . " " . $orderDirec;
        $sentencia = $this->conexion_db->prepare($sql);
        $sentencia->execute($exec);
      } else {
        $sql ="SELECT " . $busqueda . " FROM " . $tabla . " ORDER BY " . $orderBy . " " . $orderDirec;
        $sentencia = $this->conexion_db->prepare($sql);
        $sentencia->execute();
      };

      $usuario = ($todo) ? $sentencia->fetchAll(PDO::FETCH_ASSOC) : $sentencia->fetch(PDO::FETCH_ASSOC);

      $sentencia->closeCursor();
      return $usuario;
    }

    public function verifica_pass($hash, $arrayValor=array('password' => '' ))
    {
      if (isset($_POST['password'])) {
        $recibido = htmlentities(addslashes($_POST['password']));
      } elseif (isset($_GET[$campo])) {
        $recibido = htmlentities(addslashes($_GET['password']));
      } else{
        $recibido = htmlentities(addslashes($arrayValores['password']));
      };
      $recibido = html_entity_decode($recibido, ENT_QUOTES | ENT_HTML401, "UTF-8");
      $recibido = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $recibido)));

      return password_verify($recibido, $hash);
    }

    public function cerrar_conexion()
    {
      return $this->conexion_db=null;
    }

    public function registrar($tabla, $arrayCampo, $arrayValores = array()) {

      $set = '';
      $marcas = '';
      $exec = array();

      foreach ($arrayCampo as $index => $campo) {
        $marcador = ":$campo";
        $set .= "$campo, ";
        $marcas .= "$marcador, ";

        if (isset($_POST[$campo])) {
          $recibido = htmlentities(addslashes($_POST[$campo]));
        } elseif (isset($_GET[$campo])) {
          $recibido = htmlentities(addslashes($_GET[$campo]));
        } else{
          $recibido = htmlentities(addslashes($arrayValores[$campo]));
        };
        $recibido = html_entity_decode($recibido, ENT_QUOTES | ENT_HTML401, "UTF-8");
        $recibido = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $recibido)));

        $campo = mb_strtolower($campo, 'UTF-8');
        //CUIDADO AQUÍ QUE LO PASA TODO A MINÚSCULAS!

        switch ($campo) {
          case 'foto':
          case 'background':
          case 'fichero':
          case 'descripcion':
          case 'mensaje':
          case 'asunto':
          case 'fuente':
            $valor = $recibido;
            break;

          case 'password':
            $valor = password_hash($recibido, PASSWORD_DEFAULT, array('cost' =>15));
            break;

          case 'origen':
          case 'email':
          case 'emisor':
          case 'receptor':
          case 'perfilemisor':
          case 'perfilreceptor':
            $valor = mb_strtolower($recibido, 'UTF-8');
            break;

          default:
            $valor = mb_strtoupper($recibido, 'UTF-8');
            break;
        }

        $exec[$marcador] = $valor;

      };

      $set = '(' . (rtrim($set,', ')) . ')';
      $marcas = '(' . (rtrim($marcas,', ')) . ')';

      $sql = "INSERT INTO " . $tabla . " " . $set . " VALUES " . $marcas;
      $sentencia= $this->conexion_db->prepare($sql);
      $sentencia->execute($exec);
      $sentencia->closeCursor();

      $insertado = $this->buscar($tabla, $arrayCampo, '*',false,$arrayValores);

      return $insertado;
    }

    public function actualizar($tabla, $arrayCampo, $arrayWhere, $arrayValores = array(), $logic='AND') {

      $set = '';
      $where = '';
      $exec = array();
      foreach ($arrayWhere as $index => $campo) {

        $marcador = ":$campo";
        $where .= "$campo=$marcador $logic ";

        if (isset($_POST[$campo])) {
          $recibido = htmlentities(addslashes($_POST[$campo]));
        } elseif (isset($_GET[$campo])) {
          $recibido = htmlentities(addslashes($_GET[$campo]));
        } else{
          $recibido = htmlentities(addslashes($arrayValores[$campo]));
        };
        $recibido = html_entity_decode($recibido, ENT_QUOTES | ENT_HTML401, "UTF-8");
        $recibido = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $recibido)));

        $campo = mb_strtolower($campo, 'UTF-8');
        switch ($campo) {
          case 'foto':
          case 'fichero':
          case 'background':
          case 'fuente':
            $valor = $recibido;
            break;

          case 'password':
            $valor = password_hash($recibido, PASSWORD_DEFAULT, array('cost' =>15));
            break;

          case 'email':
          case 'origen':
            $valor = mb_strtolower($recibido, 'UTF-8');
            break;

          default:
            $valor = mb_strtoupper($recibido, 'UTF-8');
            break;
        }

        $exec[$marcador] = $valor;
      }

      foreach ($arrayCampo as $index => $campo) {
        $campo = mb_strtolower($campo, 'UTF-8');
        $marcador = ":$campo";
        $set .= "$campo=$marcador, ";

        if (isset($_POST[$campo])) {
          $recibido = htmlentities(addslashes($_POST[$campo]));
        } elseif (isset($_GET[$campo])) {
          $recibido = htmlentities(addslashes($_GET[$campo]));
        } else{
          $recibido = htmlentities(addslashes($arrayValores[$campo]));
        };
        $recibido = html_entity_decode($recibido, ENT_QUOTES | ENT_HTML401, "UTF-8");
        $recibido = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $recibido)));

        switch ($campo) {
          case 'foto':
          case 'background':
          case 'fuente':
            $valor = $recibido;
            break;

          case 'password':
            $valor = password_hash($recibido, PASSWORD_DEFAULT, array('cost' =>15));
            break;
          case 'email':
          case 'grado':
            $valor = mb_strtoupper($recibido, 'UTF-8');
            break;

          default:
            $valor = mb_strtolower($recibido, 'UTF-8');
            break;
        }

        $exec[$marcador] = $valor;

      };

      $where = (rtrim($where,' AND '));
      $set = (rtrim($set,', '));

      $sql = "UPDATE " . $tabla . " SET " . $set . " WHERE " . $where;

  		$sentencia= $this->conexion_db->prepare($sql);
      $sentencia->execute($exec);
      $sentencia->closeCursor();
      $actual = $this->buscar($tabla, $arrayWhere, '*', false, $arrayValores);
      return $actual;
    }

    public function obtener_ip()
    {
      if (!empty($_SERVER['HTTP_CLIENT_IP'])){
        return $_SERVER['HTTP_CLIENT_IP'];
      } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
      }else {
        return $_SERVER['REMOTE_ADDR'];
      };
    }

    public function recuperar($e,$n)
    {
      require 'PHPMailer/src/PHPMailer.php';
      require 'PHPMailer/src/SMTP.php';
      require 'PHPMailer/src/Exception.php';
      $sql ="SELECT * FROM servidor_mail WHERE accion='" . "recuperar" . "'";
      $sentencia = $this->conexion_db->prepare($sql);
      $sentencia->execute();
      $administrador = $sentencia->fetch(PDO::FETCH_ASSOC);

      $mail = new PHPMailer(true);

      // $mail->SMTPDebug = 4; //muestra errores

      $mail-> isSMTP();
      $mail-> SMTPAuth = true;
      $mail-> SMTPSecure = 'ssl';
      $mail-> Host = $administrador['host'];
      $mail-> Port = $administrador['puerto'];
      $mail-> Username = $administrador['email'];
      $mail-> Password = $administrador['password'];

      $mail-> setFrom($administrador['email'], $administrador['nombre']);


      $mail-> CharSet = 'UTF-8';
      $mail-> addAddress($e, $n);

      $mail-> Subject = $administrador['asunto'];
      $mail-> Body = file_get_contents($administrador['mensaje']);


      //se puede agregar archivos adjuntos:
      //$mail-> addAttachment('image.jpeg','name');

      $mail-> IsHTML(true);
      $enviado = $mail->send();
      $mail->ClearAddresses();

      $sentencia->closeCursor();
      // IDEA: Tienes que crear en el perfil de administrador, un contador de
      // correos, para que puedas mandar un mensaje. Además, debes verificar si
      // todos los NULL han dejado de ser NULL, sólo así dejarás de mostrarle
      // el mensaje de que tiene que rellenar eso.!!!!!!!!!

      return $enviado;
    }


  }

  function print_r_reverse($in) {
    $lines = explode("\n", trim($in));
    if (trim($lines[0]) != 'Array') {
        // bottomed out to something that isn't an array
        return $in;
    } else {
      // this is an array, lets parse it
      if (preg_match("/(\s{5,})\(/", $lines[1], $match)) {
        // this is a tested array/recursive call to this function
        // take a set of spaces off the beginning
        $spaces = $match[1];
        $spaces_length = strlen($spaces);
        $lines_total = count($lines);
        for ($i = 0; $i < $lines_total; $i++) {
          if (substr($lines[$i], 0, $spaces_length) == $spaces) {
            $lines[$i] = substr($lines[$i], $spaces_length);
          }
        }
      }
      array_shift($lines); // Array
      array_shift($lines); // (
      array_pop($lines); // )
      $in = implode("\n", $lines);
      // make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
      preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
      $pos = array();
      $previous_key = '';
      $in_length = strlen($in);
      // store the following in $pos:
      // array with key = key of the parsed array's item
      // value = array(start position in $in, $end position in $in)
      foreach ($matches as $match) {
        $key = $match[1][0];
        $start = $match[0][1] + strlen($match[0][0]);
        $pos[$key] = array($start, $in_length);
        if ($previous_key != '') $pos[$previous_key][1] = $match[0][1] - 1;
        $previous_key = $key;
      }
      $ret = array();
      foreach ($pos as $key => $where) {
        // recursively see if the parsed out value is an array too
        $ret[$key] = print_r_reverse(substr($in, $where[0], $where[1] - $where[0]));
      }
      return $ret;
    }
  };

  function generarCodigos($cantidad=5, $longitud=10, $incluyeNum=true){
    $caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $caracteres .= "abcdefghijklmnopqrstuvwxyz";
    if($incluyeNum){
      $caracteres .= "1234567890";
    }
    $arrPassResult=array();
    $index=0;
    while($index<$cantidad){
      $tmp="";
      for($i=0;$i<$longitud;$i++){
        $tmp.=$caracteres[rand(0,strlen($caracteres)-1)];
      }
      if(!in_array($tmp, $arrPassResult)){
        $arrPassResult[]=$tmp;
        $index++;
      }
    }
    return $arrPassResult[rand(0,$cantidad-1)];
  };

?>
