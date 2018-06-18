<?php
if (!isset($_POST['tabla'])) {
  header('location:../vista/index.php');
  die('No entra desde algún script');
};

require '../modelo/consulta.php';
$consulta = new Consulta;
$usuario = $consulta->usuarioConectado();
$tablaUsuario = $consulta->getTabla($_COOKIE['tabla']);
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <script type="text/javascript">

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

    function leido(idChat) {

      var datos = { accion: 'marcarLeido',
                    filaVista: idChat}
      $.post('../../controlador/actualizar_recibidos.php', datos, function (datosDevueltos) {
        $('#recibido_' + String(idChat)).toggle();
        recibidos();
      }).fail(function (xhr, status, error) {
        console.log(error);
      });
    };

    function solo(idChat) {
      $('#recibido_' + String(idChat)).toggle();
    };

    function responder(idChat, perfilEmisor) {
      $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat).toggle();
      $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' textarea[id="cuerpo_' + idChat + '"]').val('');
      $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' #msg_' + idChat).addClass('ocultar');
      $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' #msg_' + idChat).hide();
      $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #elige').addClass('disabled');
      $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #envia').addClass('disabled');
      $('#' + perfilEmisor + idChat).val('');
      verAdjunto(perfilEmisor, idChat,'');
      $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #comandos').show();
      return false;
    };

    function verAdjuntoRespuesta(perfilEmisor, idChat, nombre) {
      $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #nombreAdjunto').val(nombre);
    };

    function adjuntarRespuesta(perfilEmisor, idChat) {
      var cuerpo = $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' textarea[id="cuerpo_' + idChat + '"]').val();
      if (noVacio(cuerpo)) {
        $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #elige').removeClass('disabled');
        $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #envia').removeClass('disabled');
        return true;
      } else {
        $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #elige').addClass('disabled');
        $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #envia').addClass('disabled');
        return false;
      };
    };


    function enviarRespuesta(perfilEmisor, idChat) {
      var x = document.getElementById(perfilEmisor + idChat);
      var ficheroAdjunto = x.files[0];

      if (ficheroAdjunto != undefined) {
        var cuerpo = $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' textarea[id="cuerpo_' + idChat + '"]').val();

        if (ficheroAdjunto.size > 2097152) {
          alert('El tamaño del fichero es muy grande. Tiene que ser menor de 2MB.');
          return false;
        } else {
          $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer' + ' .loader').hide();
          var formData = new FormData();
          formData.append('fichero', ficheroAdjunto);
          formData.append('destino', idChat);
          formData.append('mensaje', cuerpo);
          formData.append('accion', 'responder');

          $.ajax({
            url: '../../controlador/actualizar_recibidos.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data, textStatus, jqXHR) {
              $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #comandos').hide();
              $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer' + ' .loader').hide();
              if (isJson(data)) {
                $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #msg_' + idChat).html('<b style="color:rgb(0, 173, 63);">El mensaje ha sido entregado satisfactoriamente.</b>');

              }else if (data == 'error_mandar') {
              $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #msg_' + idChat).html('<b style="color:rgb(255, 0, 0);">Ha ocurrido algún fallo y no se ha podido entregar el correo.</b>');
              };

              $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #msg_' + idChat).show();
              $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #msg_' + idChat).removeClass('ocultar');
              setTimeout(function () {
                $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat).toggle();
              }, 5000);

              return false;
            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.log(errorThrown);
            },
          });
        };
      } else {

        $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .loader').show();
        var cuerpo = $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' textarea[id="cuerpo_' + idChat + '"]').val();
        var datos = { mensaje: cuerpo,
                      destino: idChat,
                      accion: 'responder' };
        $.post('../../controlador/actualizar_recibidos.php', datos, function (datosDevueltos, status, jqXHR) {

          $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #comandos').hide();
          $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer' + ' .loader').hide();
          if (isJson(datosDevueltos)) {
            $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #msg_' + idChat).html('<b style="color:rgb(0, 173, 63);">El mensaje ha sido entregado satisfactoriamente.</b>');

          }else if (datosDevueltos == 'error_mandar') {
          $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #msg_' + idChat).html('<b style="color:rgb(255, 0, 0);">Ha ocurrido algún fallo y no se ha podido entregar el correo.</b>');
          };

          $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #msg_' + idChat).show();
          $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat + ' .panel-footer #msg_' + idChat).removeClass('ocultar');
          setTimeout(function () {
            $('#respuestaBody_' + perfilEmisor + ' #contestar_' + idChat).toggle();
          }, 5000);

          return false;
        }).fail(function (jqXHR, textStatus, errorThrown) {
          console.log(errorThrown);
        });

      };
      return false;

    };

    </script>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
          <?php
          $mensajesRecibidos = $consulta->busqueda(array('tabla'=>'chat', 'perfil'=>$_POST['tabla'], 'busqueda'=>$_POST['nombre']));
          ?>
          <?php if (empty($mensajesRecibidos)): ?>

            <div class="col-xs-12 col-sm-12 col-md-12">
              <div class="jumbotron text-center">
                <h2>¡La búsqueda no ha dado respuesta!</h2>
                <p><img style="height:5em;width:5em;" class="img-circle" src="../img/app/cara_triste.svg" alt="cara triste"></p>
              </div>
            </div>

          <?php endif; ?>
          <div class="panel-group col-xs-12 col-sm-12 col-md-12" id="total-recibidos">

            <?php foreach ($mensajesRecibidos as $mensaje): ?>
              <?php $autor = $consulta->buscar($mensaje['perfilEmisor'],array('email'),'*',false, array('email'=>$mensaje['emisor']));
              $fecha = date_create($mensaje['fecha']); ?>

              <div class="panel panel-default">
                <?php if ($mensaje['leido'] != 0): ?>
                  <div class="panel-heading">
                  <?php else: ?>
                  <div class="panel-heading" style="background-color: rgba(68, 180, 133, 0.6);">
                <?php endif; ?>
                  <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                      <a target="_blank" onclick=<?php
                      echo '"return verPerfil('. "'" . $mensaje['perfilEmisor'] . "','" . $autor['id'] ."')" .'";';
                      ?>><img class="img-circle" src=<?php
                      echo '"../img/perfiles/' . $autor['foto'] . '"';
                      ?> alt="foto_perfil" style="height:3em;width:3em;"></a>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-center">
                      <p><?php echo $autor['nombre']; ?><br><p><?php echo $autor['grado']; ?></p></p>
                    </div>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 text-center">
                      <p><?php echo date_format($fecha, 'd/m/Y H:i'); ?></p>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                      <?php if ($mensaje['leido']==1): ?>
                        <h4>Asunto: <a onclick=<?php
                        echo '"solo(' . $mensaje['id'] .
                        ")" . '";'; ?> style="font-size:1.25em; cursor:pointer;"><?php echo $mensaje['asunto'];?></a></h4>
                      <?php else: ?>
                        <h4>Asunto: <a onclick=<?php
                        echo '"leido(' . $mensaje['id'] .
                        ")" . '";'; ?> style="font-size:1.25em; cursor:pointer;"><?php echo $mensaje['asunto'];?></a></h4>
                      <?php endif; ?>

                    </div>
                  </div>
                </div>

                <div id=<?php echo '"recibido_' . $mensaje['id'] . '"'; ?> style="display:none;">
                  <div class="panel-body" id=<?php
                  echo '"respuestaBody_' . $mensaje['perfilEmisor'] . '"';
                  ?>>

                    <div class="panel panel-success">
                      <div class="panel-body doc_subido">
                        <div class="row">
                          <?php if (!is_null($mensaje['fichero'])){ ?>
                          <div class="col-xs-12 col-sm-1">
                            <a href=<?php echo '"../chat/' . $mensaje['fichero'] . '"'; ?>>
                              <img src="../img/app/fichero_txt.svg" alt="fichero_subido" class="img-circle" style="height:4em;width:4em;">
                            </a>
                          </div>
                          <div class="col-xs-12 col-sm-11">
                            <dl class="dl-horizontal " style="font-size: 1.2em;">
                              <dt>
                                Mensaje
                              </dt>
                              <dd>
                                <p style="font-size:0.9em;"><?php echo $mensaje['mensaje']; ?></p>
                              </dd>
                            </dl>
                          </div>
                        <?php } else {?>
                          <div class="col-xs-12 col-sm-12">
                            <dl class="dl-horizontal " style="font-size: 1.4em;">
                              <dt>
                                Mensaje
                              </dt>
                              <dd>
                                <p style="font-size:0.9em;"><?php echo $mensaje['mensaje']; ?></p>
                              </dd>
                            </dl>
                          </div>
                        <?php }; ?>
                        <?php if ($mensaje['respuesta'] == 1): ?>
                          <?php $filaRespondida = $consulta->buscar('chat', array('id'), '*', false, array('id'=>$mensaje['respuestaId'])); ?>
                          <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:0.2em;">
                            <div class="panel panel-default">
                              <div class="panel-heading">
                                <h5 class="panel-title">
                                  En respuesta a:
                                </h5>
                              </div>
                              <div class="panel-body">
                                <?php if (!is_null($filaRespondida['fichero'])){ ?>
                                <div class="col-xs-12 col-sm-1">
                                  <a href=<?php echo '"../chat/' . $mensaje['fichero'] . '"'; ?>>
                                    <img src="../img/app/fichero_txt.svg" alt="fichero_subido" class="img-circle" style="height:2em;width:2em;">
                                  </a>
                                </div>
                                <div class="col-xs-12 col-sm-11">
                                  <dl class="dl-horizontal " style="font-size: 1.2em;">
                                    <dt>
                                      Mensaje
                                    </dt>
                                    <dd>
                                      <p style="font-size:0.9em;"><?php echo $filaRespondida['mensaje']; ?></p>
                                    </dd>
                                  </dl>
                                </div>
                              <?php } else {?>
                                <div class="col-xs-12 col-sm-12">
                                  <dl class="dl-horizontal " style="font-size: 1.4em;">
                                    <dt>
                                      Mensaje
                                    </dt>
                                    <dd>
                                      <p style="font-size:0.9em;"><?php echo $filaRespondida['mensaje']; ?></p>
                                    </dd>
                                  </dl>
                                </div>
                              <?php }; ?>
                              </div>
                              <div class="panel-footer">
                                <p style="font-size:0.5em;"><?php echo $filaRespondida['fecha']; ?></p>
                              </div>
                            </div>
                          </div>

                        <?php endif; ?>

                        </div>


                      </div> <!--End panel-body (inside PANEL-BODY of panel-element-id)-->
                      <div class="panel-footer">
                        <button type="button" onclick=<?php
                        echo '"return responder('. "'" . $mensaje['id'] .
                        "','" . $mensaje['perfilEmisor'] . "')" . '";'; ?> class="btn btn-success btn-block btn-sm">
                          RESPONDER
                        </button>
                      </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12" id=<?php
                    echo '"contestar_' . $mensaje['id'] . '"';
                    ?> style="margin-top:0.3em; display:none;">
                      <div class="panel panel-warning">
                        <div class="panel-body">
                          <div class="form-horizontal">

                            <div class="form-group">
                              <label for=<?php
                              echo '"cuerpo_' . $mensaje['id'] . '"';
                              ?> class="col-xs-12 col-sm-2 col-md-2 control-label">
                                Mensaje:
                              </label>
                              <div class="col-xs-12 col-sm-10 col-md-10">
                                <textarea class="form-control" oninput=<?php
                                 echo '"return adjuntarRespuesta('. "'" . $mensaje['perfilEmisor'] . "','" . $mensaje['id'] ."')" .'";';
                                 ?> name="mensaje" id=<?php
                                echo '"cuerpo_' . $mensaje['id'] . '"';
                                ?> rows="4" cols="10" placeholder="Redactar mensaje"></textarea>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="panel-footer">
                          <div class="text-center">
                            <div id="comandos">
                              <div class="input-group">
                                <label id="elige" for=<?php
                                   echo '"'  . $mensaje['perfilEmisor'] . $mensaje['id'] . '"';
                                   ?> class="input-group-addon btn btn-primary disabled" type="button" onclick=<?php
                                 echo '"return adjuntarRespuesta('. "'" . $mensaje['perfilEmisor'] . "','" . $mensaje['id'] ."')" .'";';
                                 ?> >Adjunta&hellip; <input id=<?php
                                    echo '"'  . $mensaje['perfilEmisor'] . $mensaje['id'] . '"'; ?> type="file" accept="application/*, video/*, text/*, image/*"  onchange=<?php
                                    echo '"verAdjuntoRespuesta(' . "'" . $mensaje['perfilEmisor'] . "','" . $mensaje['id'] . "', this.files[0].name);" . '"';?> style="display: none;">
                                </label>
                                <input type="text" id='nombreAdjunto' class="form-control" readonly>
                              </div>

                              <button id="envia" class="btn btn-primary btn-xs disabled" style="margin-top:0.2em;" type="button" onclick=<?php
                               echo '"return enviarRespuesta('. "'" . $mensaje['perfilEmisor'] . "','" . $mensaje['id'] . "')" . '";';
                               ?>><span class="glyphicon glyphicon-send"></span> Enviar</button><div class="pull-right loader"></div>
                            </div>
                            <p id=<?php echo '"msg_'  .  $mensaje['id'] . '"'; ?> class="text-center ocultar"></p>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

            <?php endforeach; ?>

          </div>
        </div>
      </div>
    </div>
  </body>
</html>
<?php
$consulta->cerrar_conexion();
?>
