<?php
if (!isset($_POST['tabla'])) {
  header('location:../vista/index.php');
  die('No entra desde algún script');
};
require '../modelo/consulta.php';
$consulta = new Consulta;
$tabla = $_POST['tabla'];
$filaComentada = $_POST['filaComentada'];
$where = array('accion','origen');
$ValoresWhere = array('accion'=>'comentar',
                      'origen'=>$filaComentada);

$comentarios = $consulta->buscar($tabla, $where, '*', true, $ValoresWhere, 'AND', 'fecha','DESC');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <!-- <div class="col-xs-12 col-sm-12"> -->
        <?php if (empty($comentarios)): ?>
          <div class="col-xs-12 col-sm.12 col-md-12 jumbotron text-center" style="padding:1em;margin:0;">
            <h2 style="font-size: 1em;">¡No hay ningún comentario!</h2>
            <p style="font-size: 0.8em;">Sé el primero en comentar el documento, quizás puedas ayudar a alguien</p>
          </div>

        <?php else:
          $totalComentarios = count($comentarios);
          $comentarios_Mostrar = $consulta->get_resultadosPaginacion($tabla,$totalComentarios,'comentar',$_POST['filaComentada']);
          $comentarios = array_reverse($comentarios_Mostrar);
        ?>
        <div class="col-xs-12 col-sm-12" style="margin:0; margin-bottom:0.15em;">
          <?php if (count($comentarios) == $totalComentarios){ ?>
            <button type="button" class="col-xs-6 col-sm-4 col-md-4 btn btn-info btn-xs disabled">Ver Más</button>
          <?php }elseif (count($comentarios) < $totalComentarios) {
            $duplicado = count($comentarios)* 2 ; ?>
            <button type="button" class="col-xs-6 col-sm-4 col-md-4 btn btn-info btn-xs" onclick=<?php echo "verComen($filaComentada,$duplicado);"?> >Ver Más</button>
          <?php }; ?>

          <?php if (count($comentarios) == 1){ ?>
            <button type="button" class="col-xs-6 col-sm-4 col-md-4 btn btn-warning btn-xs disabled">Ver Menos</button>
          <?php }elseif (count($comentarios) > 1) {
            $dividido = ceil((count($comentarios)/ 2));
            //$dividido = ($dividido  >= 1)? $dividido : 2; ?>
            <button type="button" class="col-xs-6 col-sm-4 col-md-4 btn btn-warning btn-xs" onclick=<?php echo "verComen($filaComentada,$dividido);"?> >Ver Menos</button>
          <?php }; ?>
        </div>
          <?php foreach ($comentarios as $index => $fila_comentada): ?>
          <?php $comentador = $consulta->buscar($fila_comentada['perfil'], array('email'), '*', false, array('email'=>$fila_comentada['email'])); ?>

        <!-- <div class="row"> -->

        <div class="col-xs-12 col-sm-12 col-md-12">
          <div class="thumbnail" style="background-color:rgba(68, 129, 109, 0.5);">
            <img style="height:2em;width:2em;" class="img-circle"
            src="../img/perfiles/<?php echo $comentador['foto'];?>" alt="foto_login" title="<?php echo $comentador['email'];?>">
            <hr style="margin:0;padding:0;">
            <div class="caption" style="margin:0;padding:0.1em;">
              <h5 style="font-size:0.85em;"><?php
              $perfilComentador = ($fila_comentada['perfil']=='estudiantes')?'Estudiante':'Profesor(a)';
              echo $comentador['nombre'] . ' - ' . $perfilComentador;
              ?></h5>
              <p style="font-size:0.75em; color: rgba(0, 0, 0, 0.7);"><?php echo $fila_comentada['descripcion']; ?></p> <span class="text-muted"><?php $f = date_create($fila_comentada['fecha']);
                echo  date_format($f, 'd/m/Y H:i'); ?></span>
            </div>
          </div>
        </div>

        <!-- </div> -->

          <?php endforeach; ?>

        <?php endif; ?>

        <!-- </div> -->
      </div>
    </div>
  </body>
</html>
