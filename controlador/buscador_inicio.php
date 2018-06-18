<?php
if (!isset($_POST['perfil'])) {
  header('location:../vista/index.php');
  die('No entra desde algún script');
};

require '../modelo/consulta.php';
$consulta = new Consulta;
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">

    </style>
    <script type="text/javascript">

    function isJson(str) {
      try {
        JSON.parse(str);
      } catch (e) {
        return false;
      };

      return true;
    };

    function noVacio(str) {
      if (str == undefined) {
        return false;
      } else {
        var x = (str.replace('\n', ' ')).replace('/\s\s+/', ' ');
        var x = x.trim();

        if (x) {
          return true;
        }else {
          return false;
        };
      };
    };

    function pintar(id, valoracion) {
      var si = Math.floor(valoracion / 2);
      var no = (valoracion % 2);
      if (no == 0) {
        for (var i = 1; i <= si; i++) {
          $(id + ' span[id="star_' + i + '"]').addClass('total_naranja');
        };

        for (var i = si + 1; i <= 5; i++) {
          $(id + ' span[id="star_' + i + '"]').removeClass('total_naranja');
          $(id + ' span[id="star_' + i + '"]').removeClass('medio_naranaja');
        }
      } else {
        var ultima = (no + si);
        for (var i = 1; i <= si; i++) {
          $(id + ' span[id="star_' + i + '"]').addClass('total_naranja');
        };

        $(id + ' span[id="star_' + ultima + '"]').addClass('medio_naranja');
        for (var i = ultima + 1; i <= 5; i++) {
          $(id + ' span[id="star_' + i + '"]').removeClass('total_naranja');
          $(id + ' span[id="star_' + i + '"]').removeClass('medio_naranaja');
        }
      }
    };

    function verComen(id, c) {
      var data = { tabla: 'gestionFicheros', filaComentada: id };
      var c = (c == '') ? 2 : c;
      var url = '../../controlador/mostrar_comentarios.php?comentarios=' + String(c);
      $.post(url, data, function (datosDevueltos, status) {
        $('#comentarios_'+id).html(datosDevueltos);
      }).fail(function (xhr, status, error) {
        console.log(error);
      });
    };



    $(document).ready(function () {
    });
    </script>
  </head>
  <body>
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <!-- FALTA HACER UNA PAGINACIÓN PARA LAS ENTRADAS -->
        <div id="comoMostrar_documentos" class="text-center col-xs-12">
          <div class="col-xs-12 col-sm-12 col-md-12">
            <?php
            $ficherosSubidos = $consulta->busqueda(array('tabla'=>'inicio'));
            $total = count($ficherosSubidos);
            $ficherosMostrar = $consulta->get_resultadosPaginacion('gestionFicheros', $total, 'inicio');?>
            <ul class="pagination" id="paginacion_documentos">
            <?php
            $pag = $consulta->paginacion($tabla, $total);
            $total_paginas = $pag['total'];
            $mostrar = (isset($_GET['mostrar']) && $_GET['mostrar']!='') ? $_GET['mostrar'] : 15;
            $pagina = (isset($_GET['pagina']) && $_GET['pagina']!='') ? $_GET['pagina'] : 1;
            for ($i=1; $i <= $total_paginas; $i++) {
                echo "<li><a style='cursor:pointer;' onclick='return buscar" . "($mostrar,$i);" . "'>" . $i . "</a></li>";
            };
            ?>
            </ul>
          </div>
          <?php if (!empty($ficherosMostrar)): ?>
            <div class="form form-group form-inline col-xs-12 col-sm-12 col-md-12">
              <label for="mostrar_documentos">Ver: </label>
              <input type="number" class="form-control" id="mostrar_documentos" value=<?php
              echo $mostrar?> style="width:5em;" onchange="return buscar(this.value,<?php echo $pagina;?>);" min=1>
            </div>
          <?php endif; ?>
        </div>
        <?php if (empty($ficherosMostrar)): ?>

          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="jumbotron text-center">
              <h2>¡Aún no hay documentos subidos!</h2>
              <p><img style="height:5em;width:5em;" class="img-circle" src="../img/app/cara_triste.svg" alt="cara triste"></p>
              <p>¿A qué esperas? Sube apuntes y ayuda a tus compañeros</p>
            </div>
          </div>

        <?php endif; ?>

        <div class="panel-group col-xs-12 col-sm-12 col-md-12" id="panel-total">
          <?php foreach ($ficherosMostrar as $row): ?>
            <?php  $autor = $consulta->buscar($row['perfil'],array('email'),'*',false, array('email'=>$row['origen']));
            $fecha = date_create($row['fecha']);
            ?>
            <div class="panel panel-default">
              <div class="panel-heading">

                <div class="row">
                  <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                    <a target="_blank" title="Ver perfil" onclick=<?php
                    echo '"return verPerfil('. "'" . $row['perfil'] . "','" . $autor['id'] ."')" .'";';
                    ?>><img class="img-circle" src=<?php
                    echo '"../img/perfiles/' . $autor['foto'] . '"';
                    ?> alt="foto_perfil"></a><span id=<?php echo '"valor_' . $autor['id'] . '"'; ?> class="badge"><?php echo $autor['valoracion']; ?></span>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                    <p><?php echo $autor['nombre']; ?> - <?php echo ($row['perfil']=='estudiantes')?"Estudiante":"Profesor(a)"; ?><br>
                    <p style="color:rgba(0, 0, 0, 0.6);"><?php echo $autor['grado']; ?></p></p>
                  </div>
                  <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-center">
                    <p style="color:rgba(0, 0, 0, 0.6);"><?php echo date_format($fecha, 'd/m/Y H:i'); ?></p>
                  </div>
                </div>

                <div class="row">
                  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                    <p >Ha subido: <a id=<?php echo "'panel-tittle-" . $row['id'] . "'";?> class="panel-title collapsed" data-toggle='collapse' data-parent="#panel-total" href=<?php
                      echo '"#panel-element-' . $row['id'] . '"';
                      ?>><?php echo $row['nombreFichero']; ?></a> a <?php echo $row['asignatura'] ?></p>
                  </div>
                </div>

              </div>

    					<div id=<?php echo '"panel-element-' . $row['id'] . '"'; ?> class="panel-collapse collapse">
                <div class="panel-body">
                  <div class="panel panel-success">
                    <div class="panel-body doc_subido">
                      <div class="row">
                        <div class="col-xs-12 col-sm-1">
                          <a title="¡Entra y descargalos!">
                            <?php if (strpos($row['tipo'], 'IMAGE/') !== false){ ?>
                              <img src="../img/app/fichero_img.svg" alt="fichero_subido" class="img img-circle">
                            <?php } elseif (strpos($row['tipo'], 'APPLICATION/') !== false) { ?>
                              <img src="../img/app/fichero_pdf.svg" alt="fichero_subido" class="img img-circle">
                            <?php } elseif (strpos($row['tipo'], 'VIDEO/') !== false) { ?>
                              <img src="../img/app/fichero_video.svg" alt="fichero_subido" class="img img-circle">
                            <?php } elseif (strpos($row['tipo'], 'TEXT/') !== false) { ?>
                              <img src="../img/app/fichero_txt.svg" alt="fichero_subido" class="img img-circle">
                            <?php }; ?>
                          </a>
                        </div>
                        <div class="col-xs-12 col-sm-11">
                          <dl class="dl-horizontal ">
                            <dt>
                              Valoración
                            </dt>
                            <dd>
                              <!-- Pintamos el ranking -->
                              <script type="text/javascript">
                              pintar(<?php echo '"#stars-' . $row['id'] . '", ' . $row['valoracion'];?>);
                              </script>
                              <p id=<?php echo '"stars-' . $row['id'] . '"';?> >
                                <span id="star_1" class="fa fa-star"></span>
                                <span id="star_2" class="fa fa-star"></span>
                                <span id="star_3" class="fa fa-star"></span>
                                <span id="star_4" class="fa fa-star"></span>
                                <span id="star_5" class="fa fa-star"></span>
                              </p>
                            </dd>
                            <dt>
                              Nº descargas
                            </dt>
                            <dd>
                              <p><?php echo $row['descargado']; ?></p>
                            </dd>
                            <dt>
                              Descripción
                            </dt>
                            <dd>
                              <p><?php echo $row['descripcion']; ?></p>
                            </dd>
                            <dt>
                              Fuente
                            </dt>
                            <dd>
                              <p><?php echo $row['fuente']; ?></p>
                            </dd>
                          </dl>
                        </div>
                        <script type="text/javascript">
                         verComen(<?php echo '"' . $row['id'] . '"';?>, '');
                        </script>
                        <div class="text-center col-xs-12 col-sm-12" id="<?php echo "comentarios_" . $row['id'];?>">

                        </div>

                      </div>


                    </div> <!--End panel-body (inside PANEL-BODY of panel-element-id)-->


                  </div><!--End panel-succes (inside the body of panel-element-id)-->

                </div><!--End panel-body (of panel-element-id)-->
              </div><!--End panel-element-id-->
            </div><!--End panel-default-->
            <?php endforeach; ?>

        </div><!--End panel-total-->

      </div> <!--End col-md-12-->
    </div> <!--End row-->
  </body>
</html>
<?php
$consulta->cerrar_conexion();
?>
