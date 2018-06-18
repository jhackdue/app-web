<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'conexion.php';

  class Consulta extends Conexion
  {
    public $admins;

    public function __construct()
    {
      $this->admins = array();
      parent::__construct();
    }

    public function del()
    {
      if (isset($_GET["borrar"])) {
        $id = $_GET["borrar"];

        $exito = $this->borrar('servidor_mail', $id);
        $pag = $this->paginacion();
        $total_paginas = $pag['total'];
        $total_registros = $pag['registros'];

        $s = "SELECT * FROM servidor_mail WHERE id>$id";
        $consulta = $this->conexion_db->query($s);

        while ($registro=$consulta->fetch(PDO::FETCH_ASSOC)) {
            $oldId = $registro['id'];
            $sql = "UPDATE servidor_mail SET id=" . $id . " WHERE id=" . $oldId;
            $otraConsulta = $this->conexion_db->query($sql);
            $otraConsulta->closeCursor();
            $id = $id + 1;
        }
        $consulta->closeCursor();
        $this->restaurar_incremento('servidor_mail', $total_registros);

        if ($exito) {
          header("location:servidor.php?pagina=$total_paginas");
        } else {
          echo "error al eliminar";
        }
      }
      return 0;

    }

    public function borrar($tabla, $id)
    {
      $sql = "DELETE FROM " . $tabla . " WHERE id = " . $id;
      $sentencia = $this->conexion_db->query($sql);
      $sentencia->closeCursor();
      return true;
    }

    public function restaurar_incremento($tabla, $v)
    {
      $new_inc = $v + 1;
      $sql ="ALTER TABLE " . $tabla . "  auto_increment = " . $new_inc ;
      $sentencia = $this->conexion_db->query($sql);
      $sentencia->closeCursor();
      return 0;
    }

    public function act($tabla) {

      if (isset($_POST["actualizado"])){
        $id = $_POST["id"];
        $old_id = $_POST["old"];
        $nombre = $_POST["nombre"];
        $accion = $_POST["accion"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $puerto = $_POST["puerto"];
        $host = $_POST["host"];
        $asunto = $_POST["asunto"];
        $mensaje = $_POST["mensaje"];

        $sql = "UPDATE " . $tabla . " SET id=:id, nombre=:nom, accion=:acc, " .
               "email=:ema, password=:pass, puerto=:pue, host=:hos, " .
               "asunto=:asu, mensaje=:men WHERE id=:old";

        $resultado = $this->conexion_db->prepare($sql);

        $resultado->execute(array(":id"=>$id, ":old"=>$old_id, ":nom"=>$nombre,
                    ":acc"=>$accion, ":ema"=>$email, ":pass"=>$password,
                    ":pue"=>$puerto, ":hos"=>$host, ":asu"=>$asunto, ":men"=>$mensaje));
        $resultado->closeCursor();
        $total_registros = $this->total('servidor_mail');
        $this->restaurar_incremento('servidor_mail', $total_registros);
        header("location:servidor.php");
      }
    }

    public function crear()
    {
      if (isset($_POST["crear"])) { // si has pulsado el boton de insertar
        if ($_POST["nombre"]==" " || $_POST["accion"]==" " ||
            $_POST["password"]==" "|| $_POST["host"]==" "|| $_POST["puerto"]==" " ||
            $_POST["asunto"]==" "||$_POST["mensaje"]==" ") {
          echo "No puedes dejar ninguna casilla en blanco";

        }else {
          $exito = $this->insertar_servidor('servidor_mail');
          if ($exito) {
            $pag = $this->paginacion();
            $total_paginas = $pag['total'];
            header("location:servidor.php?pagina=$total_paginas");
          } else {
            echo "Error";
          }
        }
      }
      return 0;
    }


    public function paginacion()
    {
      $mostrar = (isset($_GET['mostrar'])) ? $_GET['mostrar'] : 3 ;

      if (isset($_GET["pagina"])) {
          if ($_GET["pagina"]<=1) {
              header("location:servidor.php");
          }else {
              $pagina=$_GET["pagina"];
          }
      }else {
          $pagina=1;// página actual en la que empezamos el index
      };

      $empezar_desde = ($pagina-1)*$mostrar;
      $total_registros = $this->total('servidor_mail');
      $total_paginas = ceil($total_registros / $mostrar);

      return array('empezar'=> $empezar_desde , 'total'=>$total_paginas,
                   'mostrar'=>$mostrar, 'registros'=>$total_registros);
    }

    public function total($tabla)
    {
      $sql ="SELECT * FROM " . $tabla ;
      $sentencia = $this->conexion_db->query($sql);
      $num_registros = $sentencia->rowCount();
      $sentencia->closeCursor();

      return $num_registros;
    }




    public function get_admins()
    {
      $pag = $this->paginacion();
      $empezar_desde = $pag['empezar'];
      $mostrar = $pag['mostrar'];

      $s = "SELECT * FROM servidor_mail LIMIT $empezar_desde, $mostrar";
      $consulta = $this->conexion_db->query($s);

      while ($registro=$consulta->fetch(PDO::FETCH_ASSOC)) {
          $this->admins[]=$registro; // almacenamos el registro en el array productos
      }
      $consulta->closeCursor();
      return $this->admins;
    }


    public function insertar_servidor($tabla)
    {
      $nombre = $_POST["nombre"];
      $accion = $_POST["accion"];
      $email = $_POST["email"];
      $password = $_POST["password"];
      $puerto = $_POST["puerto"];
      $host = $_POST["host"];
      $asunto = $_POST["asunto"];
      $mensaje = $_POST["mensaje"];

      $sql = "INSERT INTO " . $tabla . " (nombre, accion, email, password,
      puerto, host, asunto, mensaje) VALUES (:nom, :ac, :email,
      :pass, :puerto, :host, :asunto, :mensaje)";

      $resultado = $this->conexion_db->prepare($sql);

      $resultado->execute(array(":nom"=>$nombre, ":ac"=>$accion,
      ":email"=>$email, ":pass"=>$password, ":puerto"=>$puerto,
      ":host"=>$host, ":asunto"=>$asunto, ":mensaje"=>$mensaje));

      $resultado->closeCursor();
      return true;
    }

    public function existe($table, $campo)
    {
      $post= htmlentities(addslashes($_POST[$campo]));
      $sql ="SELECT * FROM " . $table . " WHERE " . $campo . "= :valor";
      $sentencia = $this->conexion_db->prepare($sql);
      $sentencia->bindValue(":valor", $post);
      $sentencia->execute();

      $registros_afectados = $sentencia->rowCount();
      $sentencia->closeCursor();

      if ($registros_afectados == 0) {
        return false;
      } else {
        return true;
      };
    }


    public function buscar($tabla, $campo)
    {
      $post= htmlentities(addslashes($_POST[$campo]));
      $sql ="SELECT * FROM " . $tabla . " WHERE " . $campo . "= :valor";
      $sentencia = $this->conexion_db->prepare($sql);
      $sentencia->bindValue(":valor", $post);
      $sentencia->execute();
      $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);
      $sentencia->closeCursor();
      return $usuario;
    }

    public function verifica_pass($p)
    {
      $password_post= htmlentities(addslashes($_POST["password"]));
      return password_verify($password_post, $p);
    }

    public function cerrar_conexion()
    {
      return $this->conexion_db=null;
    }

    public function registrar()
    {
      $sql = "INSERT INTO administracion (nombre, apellidos, email, password)
              VALUES (:nom, :ape, :email, :pass)";

  		$sentencia = $this->conexion_db->prepare($sql);

      $nombre = ucwords(strtolower(htmlentities(addslashes($_POST["nombre"]))));
      $nombre = html_entity_decode($nombre, ENT_QUOTES | ENT_HTML401, "UTF-8");

      $ape = ucwords(strtolower(htmlentities(addslashes($_POST["apellidos"]))));
      $ape = html_entity_decode($ape, ENT_QUOTES | ENT_HTML401, "UTF-8");

      $email = htmlentities(addslashes($_POST["email"]));

      $pass = htmlentities(addslashes($_POST["password"]));
      $pass_cifrado = password_hash($pass, PASSWORD_DEFAULT, array('cost' =>15));

  		$sentencia->execute(array(":nom"=>$nombre, ":ape"=>$ape,
                                ":email"=>$email,":pass"=>$pass_cifrado));

  		$sentencia->closeCursor();

      $sql2 ="SELECT * FROM administracion WHERE email= :email";
      $sentencia2 = $this->conexion_db->prepare($sql2);
      $sentencia2->bindValue(":email", $email);
      $sentencia2->execute();
      $usuario = $sentencia2->fetch(PDO::FETCH_ASSOC);
      $sentencia2->closeCursor();
      return $usuario;
    }

    public function registrar_mail()
    {
      $sql = "INSERT INTO servidor_mail (nombre, accion, email, password,
              puerto, host, asunto, mensaje) VALUES (:nom, :ac, :email, :pass,
              :pt, :ht, :asto, :msj)";

  		$sentencia = $this->conexion_db->prepare($sql);


      $n = ucwords(strtolower(htmlentities(addslashes($_POST["nombre"]))));
      $n = html_entity_decode($n, ENT_QUOTES | ENT_HTML401, "UTF-8");

      $ac = ucwords(strtolower(htmlentities(addslashes($_POST["accion"]))));
      $ac = html_entity_decode($ac, ENT_QUOTES | ENT_HTML401, "UTF-8");

      $e = htmlentities(addslashes($_POST["email"]));
      $e = html_entity_decode($e, ENT_QUOTES | ENT_HTML401, "UTF-8");

      $pass = htmlentities(addslashes($_POST["password"]));
      $pass = html_entity_decode($pass, ENT_QUOTES | ENT_HTML401, "UTF-8");

      $pt = htmlentities(addslashes($_POST["puerto"]));

      $ht = htmlentities(addslashes($_POST["host"]));
      $ht = html_entity_decode($ht, ENT_QUOTES | ENT_HTML401, "UTF-8");

      $asto = ucwords(strtolower(htmlentities(addslashes($_POST["asunto"]))));
      $asto = html_entity_decode($asto, ENT_QUOTES | ENT_HTML401, "UTF-8");

      $msj = htmlentities(addslashes($_POST["mensaje"]));
      $msj = html_entity_decode($msj, ENT_QUOTES | ENT_HTML401, "UTF-8");


  		$sentencia->execute(array(":nom"=>$n, ":ac"=>$ac, ":email"=>$e,
                                ":pass"=>$pass, ":pt"=>$pt, ":ht"=>$ht,
                                ":asto"=>$asto, ":msj"=>$msj));

  		$sentencia->closeCursor();

      $sql2 ="SELECT * FROM servidor_mail WHERE accion= :ac";
      $sentencia2 = $this->conexion_db->prepare($sql2);
      $sentencia2->bindValue(":ac", $ac);
      $sentencia2->execute();
      $admin = $sentencia2->fetch(PDO::FETCH_ASSOC);
      $sentencia2->closeCursor();
      return $admin;
    }

    // public function obtener_ip()
    // {
    //   if (!empty($_SERVER['HTTP_CLIENT_IP'])){
    //     return $_SERVER['HTTP_CLIENT_IP'];
    //   } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
    //     return $_SERVER['HTTP_X_FORWARDED_FOR'];
    //   }else {
    //     return $_SERVER['REMOTE_ADDR'];
    //   };
    // }

  //   public function modificar_contra()
  //   {
  //
  //     $sql = "UPDATE estudiantes SET password=:pass WHERE email=:e";
  //     $sentencia= $this->conexion_db->prepare($sql);
  //     $exito = false;
  //     if (isset($_GET['email']) and isset($_POST['pass']) ) {
  //       $email = $_GET['email'];
  //       $password = $_POST['pass'];
  //       $pass_cifrado = password_hash($password, PASSWORD_DEFAULT, array('cost' =>15));
  //       $sentencia->execute(array(":pass"=>$pass_cifrado, ":e"=>$email));
  //       $filas_modificadas = $sentencia->rowCount();
  //       if ($filas_modificadas != 0) {
  //         $exito = true;
  //       } else {
  //         $exito = false;
  //       };
  //     };
  //     $sentencia->closeCursor();
  //     return $exito;
  //   }
  //
  //   public function recuperar($e,$n)
  //   {
  //     require 'PHPMailer/src/PHPMailer.php';
  //     require 'PHPMailer/src/SMTP.php';
  //     require 'PHPMailer/src/Exception.php';
  //     $sql ="SELECT * FROM administracion WHERE accion = 'recuperar'";
  //     $sentencia = $this->conexion_db->prepare($sql);
  //     $sentencia->execute();
  //     $administrador = $sentencia->fetch(PDO::FETCH_ASSOC);
  //
  //     $mail = new PHPMailer(true);
  //
  //     // $mail->SMTPDebug = 4; //muestra errores
  //
  //     $mail-> isSMTP();
  //     $mail-> SMTPAuth = true;
  //     $mail-> SMTPSecure = 'ssl';
  //     $mail-> Host = $administrador['host'];
  //     $mail-> Port = $administrador['puerto'];
  //     $mail-> Username = $administrador['email'];
  //     $mail-> Password = $administrador['password'];
  //
  //     $mail-> setFrom($administrador['email'], $administrador['nombre']);
  //
  //     $mail-> CharSet = 'UTF-8';
  //     $mail-> addAddress($e, $n);
  //
  //     $mail-> Subject = $administrador['asunto'];
  //     // $mail-> Body = file_get_contents('../vista/recuperar/mensaje.html');
  //     $mail-> Body = file_get_contents($administrador['mensaje']);
  //
  //
  //     //se puede agregar archivos adjuntos:
  //     //$mail-> addAttachment('image.jpeg','name');
  //
  //     $mail-> IsHTML(true);
  //     $enviado = $mail->send();
  //     $mail->ClearAddresses();
  //
  //     $sentencia->closeCursor();
  //
  //     return $enviado;
  //   }
  //
  //
  // IDEA: Crear una tabla de administradores con sus acciones y rellenar otra
  // tabla con los datos de correos: Table servidor_mail: puerto|host|asunto|msj
  /*
  CREATE TABLE `tfg`.`servidor_mail` ( `id` INT(3) NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(100) NOT NULL , `accion` VARCHAR(20) NOT NULL ,
  `email` VARCHAR(100) NOT NULL , `password` VARCHAR(100) NOT NULL ,
  `puerto` INT(3) NOT NULL , `host` VARCHAR(30) NOT NULL ,
  `asunto` VARCHAR(30) NOT NULL , `mensaje` TEXT NOT NULL , PRIMARY KEY (`id`))
  ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;
  */
  }
?>
