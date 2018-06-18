<?php
if (!isset($_POST['tabla'])) {
  header('location:../vista/index.php');
  die('No entra desde algún script');
};

require '../modelo/consulta.php';
$consulta = new Consulta;
$usuario = $consulta->usuarioConectado();
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

    function modalNuevo(id) {
      $('#container_' + id + ' input[name=perfilAsig]').prop('checked', false);
      $('#container_' + id + ' input[name=fuente]').val('');
      $('#container_' + id + ' input[name=titulo]').val('');
      $('#container_' + id + ' textarea[name=descripcion]').val('');
      $('#msg_' + id).addClass('ocultar');
      $('#msg_' + id).hide();
      $('#container_' + id + ' .modal-footer #elige').addClass('disabled');
      $('#container_' + id + ' .modal-footer #envia').addClass('disabled');
      $('#' + id).val("");
      verFile(id,'');
      $('#container_' + id + ' .modal-footer #comandos').show();

      return true;
    };

    function selec(id) {
      var tipo = $('#container_' + id +' input[name=perfilAsig]:checked').val();
      var fuente = $('#container_' + id + ' input[name=fuente]').val();
      var titulo = $('#container_' + id + ' input[name=titulo]').val();
      var descripcion = $('#container_' + id + ' textarea[name=descripcion]').val();
      if (noVacio(tipo) && noVacio(fuente) && noVacio(titulo) && noVacio(descripcion)) {
        $('#container_' + id + ' .modal-footer #elige').removeClass('disabled');
        $('#container_' + id + ' .modal-footer #envia').removeClass('disabled');
        return true
      } else {
        $('#container_' + id + ' .modal-footer #elige').addClass('disabled');
        $('#container_' + id + ' .modal-footer #envia').addClass('disabled');
        verFile(id,'');
        return false
      };
    };

    function verFile(id, nombre) {
      $('#container_' + id + ' .modal-footer #nombreFile').val(nombre);
    }

    function isJson(str) {
      try {
        JSON.parse(str);
      } catch (e) {
        return false;
      };

      return true;
    };

    function cambiar(id) {

      var exito = false;
      var formData = new FormData();
      var x = document.getElementById(id);
      var asignatura = x.name;
      var dato = x.files[0];
      var tipo = $('#container_' + id +' input[name=perfilAsig]:checked').val();
      var fuente = $('#container_' + id + ' input[name=fuente]').val();
      var titulo = $('#container_' + id + ' input[name=titulo]').val();
      var descripcion = $('#container_' + id + ' textarea[name=descripcion]').val();


      // if (dato.size > 1048576) {
      if (dato.size > 2097152) {
        exito = false;
        $('#container_' + id + ' .modal-footer #comandos').hide();
        $('#msg_' + id).html('<b style="color:rgba(84, 47, 68, 0.86);">El tamaño del fichero es muy grande. Tiene que ser menor de 2MB.</b>');
        $('#msg_' + id).show();
        $('#msg_' + id).removeClass('ocultar');
      } else {
        exito = true;
      };

      if (exito) {
        $('.loader').show();
        formData.append(asignatura, dato);
        formData.append('asignatura', asignatura);
        formData.append('tabla','gestionFicheros');
        formData.append('perfilFichero', tipo);
        formData.append('fuente', fuente);
        formData.append('titulo', titulo);
        formData.append('descripcion', descripcion);
        formData.append('accion', 'subir');

        $.ajax({
          url: '../../controlador/actualizar_fichero.php',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (data, textStatus, jqXHR) {

            // console.log(data + '\n' + textStatus + '\n' + jqXHR);
            $('#container_' + id + ' .modal-footer #comandos').hide();
            //$('.loader').hide();
            if (isJson(data)) {
              $('#msg_' + id).html('<b style="color:rgb(0, 173, 63);">El fichero se ha sido subido correctamente</b>');

            }else if (data == 'error_existe') {
              $('#msg_' + id).html('<b style="color:rgb(227, 145, 23);">Este fichero ya existe en esta asignatura. Búscala en la navegación ¡Arriba a la izquierda!.</b>');
            }else if (data == 'error_tamaño' ||
                      data == 'error_upload' ||
                      data == 'no_enTemporal') {
              $('#msg_' + id).html('<b style="color:rgba(84, 47, 68, 0.86);">Has sobrepasado el tamaño permitido. Debe ser menos de 2MB.</b>');
            }else if (data == 'error_deTemporal_aCarpeta') {
              $('#msg_' + id).html('<b style="color:rgb(255, 0, 0);">Ha ocurrido algún fallo y no se ha podido subir el archivo.</b>');
            }else if (data == 'error_formato') {
              $('#msg_' + id).html('<b style="color:rgb(131, 0, 39);">No se aceptan ficheros en este formato.</b>');
            };

            $('#msg_' + id).show();
            $('#msg_' + id).removeClass('ocultar');
            setTimeout(function () {
              $('#container_'+id).modal('toggle');
              documentosPublicados('',1);
              perfil(); //para poner foto al usuario para comentar
            }, 2000);

            return false;
          },

          error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
          },
        });
      };

    };

    function contactar(tabla, id) {
      $('#contactos_' + tabla + ' #mensaje_' + id).toggle();
      $('#contactos_' + tabla + ' #mensaje_' + id + ' textarea[id="cuerpo_' + id + '"]').val('');
      $('#contactos_' + tabla + ' #mensaje_' + id + ' input[name="asunto"]').val('');
      $('#contactos_' + tabla + ' #mensaje_' + id + ' #msg_' + id).addClass('ocultar');
      $('#contactos_' + tabla + ' #mensaje_' + id + ' #msg_' + id).hide();
      $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #elige').addClass('disabled');
      $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #envia').addClass('disabled');
      $('#' + tabla + id).val('');
      verAdjunto(tabla, id,'');
      $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #comandos').show();
      return false;
    };

    function adjuntar(tabla, id) {
      var cuerpo = $('#contactos_' + tabla + ' #mensaje_' + id + ' textarea[id="cuerpo_' + id + '"]').val();
      var asunto = $('#contactos_' + tabla + ' #mensaje_' + id + ' input[name="asunto"]').val();
      if (noVacio(cuerpo) && noVacio(asunto)) {
        $('#contactos_' + tabla + ' .panel-footer #elige').removeClass('disabled');
        $('#contactos_' + tabla + ' .panel-footer #envia').removeClass('disabled');
        return true;
      } else {
        $('#contactos_' + tabla + ' .panel-footer #elige').addClass('disabled');
        $('#contactos_' + tabla + ' .panel-footer #envia').addClass('disabled');
        verFile(id,'');
        return false;
      };
    };

    function verAdjunto(tabla, id, nombre) {
      $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #nombreAdjunto').val(nombre);
    };

    function enviarMensaje(tabla, id) {
      var x = document.getElementById(tabla + id);
      var ficheroAdjunto = x.files[0];
      if (ficheroAdjunto != undefined) {
        var asunto = $('#contactos_' + tabla + ' #mensaje_' + id + ' input[name="asunto"]').val();
        var cuerpo = $('#contactos_' + tabla + ' #mensaje_' + id + ' textarea[id="cuerpo_' + id + '"]').val();
        var formData = new FormData();
        if (ficheroAdjunto.size > 2097152) {
          alert('El tamaño del fichero es muy grande. Tiene que ser menor de 2MB.');
          return false;
        } else {
          $('#contactos_' + tabla + ' #mensaje_' + id + ' .loader').show();
          formData.append('fichero', ficheroAdjunto);
          formData.append('destino', id);
          formData.append('perfilReceptor', tabla);
          formData.append('asunto', asunto);
          formData.append('mensaje', cuerpo);
          formData.append('accion', 'contactar');

          $.ajax({
            url: '../../controlador/actualizar_fichero.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data, textStatus, jqXHR) {
              $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #comandos').hide();
              $('#contactos_' + tabla + ' #mensaje_' + id + ' .loader').hide();
              if (isJson(data)) {
                $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #msg_' + id).html('<b style="color:rgb(0, 173, 63);">Se ha enviado el mensaje</b>');

              }else if (data == 'error_mandar') {
                $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #msg_' + id).html('<b style="color:rgb(255, 0, 0);">Ha ocurrido algún fallo y no se ha podido enviar el mensaje.</b>');
              };

              $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #msg_' + id).show();
              $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #msg_' + id).removeClass('ocultar');
              setTimeout(function () {
                $('#contactos_' + tabla + ' #mensaje_' + id).toggle();
                // Ejecutar función que actualice mensajes enviados;
              }, 5000);

              return false;
            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.log(errorThrown);
            },
          });
        };
      } else {
        $('#contactos_' + tabla + ' #mensaje_' + id + ' .loader').show();
        var titulo = $('#contactos_' + tabla + ' #mensaje_' + id + ' input[name="asunto"]').val();
        var cuerpo = $('#contactos_' + tabla + ' #mensaje_' + id + ' textarea[id="cuerpo_' + id + '"]').val();
        var datos = { perfilReceptor: tabla,
                      asunto: titulo,
                      mensaje: cuerpo,
                      destino: id,
                      accion: 'contactar' };
        $.post('../../controlador/actualizar_fichero.php', datos, function (datosDevueltos, status, jqXHR) {
          $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #comandos').hide();
          $('#contactos_' + tabla + ' #mensaje_' + id + ' .loader').hide();
          if (isJson(datosDevueltos)) {
            $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #msg_' + id).html('<b style="color:rgb(0, 173, 63);">El mensaje ha sido entregado satisfactoriamente.</b>');

          }else if (datosDevueltos == 'error_mandar') {
            $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #msg_' + id).html('<b style="color:rgb(255, 0, 0);">Ha ocurrido algún fallo y no se ha podido entregar el correo.</b>');
          };

          $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #msg_' + id).show();
          $('#contactos_' + tabla + ' #mensaje_' + id + ' .panel-footer #msg_' + id).removeClass('ocultar');
          setTimeout(function () {
            $('#contactos_' + tabla + ' #mensaje_' + id).toggle();
            // Ejecutar función que actualice mensajes enviados;
          }, 5000);

          return false;
        }).fail(function (jqXHR, textStatus, errorThrown) {
          console.log(errorThrown);
        });

      };
      return false;

    };

    $(document).ready(function () {
    });
    </script>
  </head>
  <body>
    <?php if ($_POST['tabla']=='asignaturas'):
            $resultado = $consulta->busqueda();?>
      <?php foreach ($resultado as $index => $array): ?>
        <div class="list-group list-group-flush text-center">
          <a id=<?php echo '"modal_' . $array['id'] . '"';?> href=<?php
          echo '"#container_' . $array['id'] . '"';
          ?> data-toggle="modal" class="list-group-item" onclick=<?php
          echo '"return modalNuevo(' . "'" .  $array['id'] . "');" . '"';
          ?>>
          <span class="badge">Subir</span> <?php echo $array['asignatura'];?>
          </a>
        </div>

        <div class="modal fade" id=<?php echo '"container_' . $array['id'] . '"'; ?> role="dialog" aria-labelledby="Subir_Apuntes" aria-hidden="true">

          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title text-center" id="Subir_Apuntes"><?php echo $array['asignatura'] ?></h4>
              </div>
              <div class="modal-body">

                <div class="form-group form-inline">
                  <label class="control-label" for=<?php echo '"p_' . $array['id'] . '"';?> >Tipo</label>
                  <div class="pull-right">
                    <div class="form-control-static text-center" id=<?php echo '"p_' . $array['id'] . '"';?>>

                      <label class="radio-inline">
                        <input type="radio" oninput=<?php echo '"selec(' . "'" .  $array['id'] . "');" . '"';?> name="perfilAsig" value="teoría">Teoría
                      </label>
                      <label class="radio-inline">
                        <input type="radio" oninput=<?php echo '"selec(' . "'" .  $array['id'] . "');" . '"';?> name="perfilAsig" value="práctica">Práctica
                      </label>
                      <label class="radio-inline">
                        <input type="radio" oninput=<?php echo '"selec(' . "'" .  $array['id'] . "');" . '"';?> name="perfilAsig" value="examen">Examen
                      </label>

                    </div>

                  </div>
                </div>

                <div class="form-group form-inline">
                  <label for="fuente" class="control-label">
                    Fuente:
                  </label>
                  <div class="pull-right">
                    <input class="form-control" oninput=<?php echo '"selec(' . "'" .  $array['id'] . "');" . '"';?> id="fuente" type="text" name="fuente" />
                  </div>
                </div>

                <div class="form-group form-inline">
                  <label for="titulo" class="control-label">
                    Título del Fichero:
                  </label>
                  <div class="pull-right">
                    <input class="form-control" oninput=<?php echo '"selec(' . "'" .  $array['id'] . "');" . '"';?> id="titulo" type="text" name="titulo" />
                  </div>
                </div>

                <div class="form-group form-inline">
                  <label for="descripcion">Descripción:</label>
                  <div class="pull-right">
                    <textarea class="form-control" oninput=<?php echo '"selec(' . "'" .  $array['id'] . "');" . '"';?> rows="2" id="descripcion" name="descripcion"></textarea>

                  </div>
                </div>
              </div>
  						<div class="modal-footer">
                <div class="text-center">
                  <div id="comandos">
                    <div class="input-group">

                      <label id="elige" for=<?php echo '"'  .  $array['id'] . '"'; ?> class="input-group-addon btn btn-primary disabled" type="button" onclick=<?php
                      echo '"return selec(' . "'" .  $array['id'] . "');" . '"';
                      ?> >Elige Fichero&hellip; <input id=<?php
                          echo '"'  .  $array['id'] . '"'; ?> type="file" accept="application/*, video/*, text/*, image/*" name=<?php
                          echo '"'  .  $array['asignatura'] . '"'; ?> onchange=<?php echo '"verFile('  . "'" . $array['id'] . "', this.files[0].name);" . '"';?> style="display: none;">
                      </label>
                      <input type="text" id='nombreFile' class="form-control" readonly>
                    </div>

                    <button id="envia" class="btn btn-primary btn-xs disabled" style="margin-top:0.2em;" type="button" onclick=<?php
                    echo '"cambiar(' . "'" .  $array['id'] . "');" . '"';
                    ?>><span class="glyphicon glyphicon-send"></span> Enviar</button><div class="pull-right loader"></div>
                  </div>
                  <p id=<?php echo '"msg_'  .  $array['id'] . '"'; ?> class="text-center ocultar"></p>

                </div>
              </div>

            </div>
          </div> <!-- cerramos modal-dialog-->
        </div>

      <?php endforeach; ?>
    <?php endif; ?>

    <?php
      if ($_POST['tabla'] == 'estudiantes' || $_POST['tabla'] == 'profesores'):
        $resultado = $consulta->busqueda();
    ?>
        <?php if (empty($resultado)): ?>
          <div class="container-fluid">
            <div class="row">
              <div class="jumbotron well col-xs-12 col-sm-12 col-md-12">
                <h5>Este contacto no está registrado</h5>
                <p><img src="../img/app/confused.svg" alt="no results" style="height:4em;width:4em;"></p>
              </div>
            </div>
          </div>
        <?php else: ?>
          <?php foreach ($resultado as $index => $result): ?>
            <div id=<?php
            echo '"contactos_' . $_POST['tabla'] . '"';
            ?> class="container-fluid" style="margin:1em 0;border-radius:1em;border: 2px solid rgb(76, 153, 144); background-color:rgba(143, 143, 143, 0.3);">
              <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12" style=<?php echo
                '"background-image: url(' . "'../img/backgrounds/" . $result['background'] .
                "');background-size:cover;background-position:center;" .
                "border-radius: 0.8em 0.8em 0px 0px;background-repeat:no-repeat;height:4em;" .
                "position:relative;z-index:1;border-bottom: 2px solid rgb(76, 153, 144);" . '"';?>>
                <a target="_blank" onclick=<?php
                echo '"return verPerfil('. "'" . $_POST['tabla'] . "','" . $result['id'] ."')" .'";';
                ?>><img class="img-circle" title="Ver Perfil" style="height:3em;width:3em;border: 2px solid rgb(76, 153, 144);margin-top:2.5em;position:aboslute;z-index:2; background-color:rgb(181, 215, 65);" alt="foto_perfil" src=<?php
                echo '"../img/perfiles/' . $result['foto'] . '"'; ?> ></a><span id=<?php echo '"valorUsuario_' . $result['id'] . '"'; ?> class="badge"><?php echo $result['valoracion']; ?></span>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top:1.8em;">
                  <h4 class="text-center"><?php echo $result['nombre'];?></h4>
                  <p class="text-center">
                    <a class="btn btn-primary btn-xs" onclick=<?php
                     echo '"return contactar('. "'" . $_POST['tabla'] . "','" . $result['id'] ."')" .'";';
                     ?>>Contacta</a> <select id=<?php
                     echo '"' . "valorarUsuario_" . $result['id'] . '"';?> onchange=<?php echo '"return valorar(' . "'" . $result['id'] . "','" . $_POST['tabla'] ."');" . '"';?>>
                     <option value="">Valora</option>
                     <option value=1>1</option>
                     <option value=2>2</option>
                     <option value=3>3</option>
                     <option value=4>4</option>
                     <option value=5>5</option>
                     <option value=6>6</option>
                     <option value=7>7</option>
                     <option value=8>8</option>
                     <option value=9>9</option>
                     <option value=10>10</option>
                   </select>
                 </p>
               </div>
               <div class="col-xs-12 col-sm-12 col-md-12" id=<?php
               echo '"mensaje_' . $result['id'] . '"';
               ?> style="margin-top:0.3em; display:none;">
                 <div class="panel panel-warning">
                   <div class="panel-heading">
                     <h3 class="panel-title">
                       Mensaje a  <?php echo ucwords(mb_strtolower($result['nombre'], 'UTF-8'));?>
                     </h3>
                   </div>
                   <div class="panel-body">
                     <div class="form-horizontal">
                       <div class="form-group">
                         <label for="asunto">Asunto:</label>
                         <input type="text" id="asunto" name="asunto" oninput=<?php
                          echo '"return adjuntar('. "'" . $_POST['tabla'] . "','" . $result['id'] ."')" .'";';
                          ?>>
                       </div>
                       <div class="form-group">
                         <label for=<?php
                         echo '"cuerpo_' . $result['id'] . '"';
                         ?> class="col-xs-12 col-sm-2 col-md-2 control-label">
                           Mensaje:
                         </label>
                         <div class="col-xs-12 col-sm-10 col-md-10">
                           <textarea class="form-control" oninput=<?php
                            echo '"return adjuntar('. "'" . $_POST['tabla'] . "','" . $result['id'] ."')" .'";';
                            ?> name="mensaje" id=<?php
                           echo '"cuerpo_' . $result['id'] . '"';
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
                              echo '"'  . $_POST['tabla'] . $result['id'] . '"';
                              ?> class="input-group-addon btn btn-primary disabled" type="button" onclick=<?php
                            echo '"return adjuntar('. "'" . $_POST['tabla'] . "','" . $result['id'] ."')" .'";';
                            ?> >Adjunta&hellip; <input id=<?php
                               echo '"'  . $_POST['tabla'] . $result['id'] . '"'; ?> type="file" accept="application/*, video/*, text/*, image/*"  onchange=<?php
                               echo '"verAdjunto(' . "'" . $_POST['tabla'] . "','" . $result['id'] . "', this.files[0].name);" . '"';?> style="display: none;">
                           </label>
                           <input type="text" id='nombreAdjunto' class="form-control" readonly>
                         </div>

                         <button id="envia" class="btn btn-primary btn-xs disabled" style="margin-top:0.2em;" type="button" onclick=<?php
                          echo '"return enviarMensaje('. "'" . $_POST['tabla'] . "','" . $result['id'] . "')" . '";';
                          ?>><span class="glyphicon glyphicon-send"></span> Enviar</button><div class="pull-right loader"></div>
                       </div>
                       <p id=<?php echo '"msg_'  .  $result['id'] . '"'; ?> class="text-center ocultar"></p>

                     </div>
                   </div>
                 </div>
               </div>
             </div>
           </div>
           <script type="text/javascript">
             valorar(<?php echo '"' . $result['id'] . '","' . $_POST['tabla'] . '"';?>);
           </script>
          <?php endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>
    <?php $consulta->cerrar_conexion(); ?>



  </body>
</html>
