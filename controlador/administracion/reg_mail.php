<?php
if (!isset($_SESSION["administrador"])) {
    header("location:../../vista/administracion/index.php");
    die('No hay sesión creada');
};
require '../../modelo/admin_consulta.php';
$consulta = new Consulta();
$matrizAdmins = $consulta->get_admins();
$consulta->crear();
$consulta->del();
$consulta->act('servidor_mail');
// echo "old_id: " . $_GET['id'];
?>
<form class="" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

<table width="60%" border="0" align="center">
  <tr >
    <td class="primera_fila">Id</td>
    <td class="primera_fila">Nombre</td>
    <td class="primera_fila">Accion</td>
    <td class="primera_fila">Email</td>
    <td class="primera_fila">Password</td>
    <td class="primera_fila">Puerto</td>
    <td class="primera_fila">Host</td>
    <td class="primera_fila">Asunto</td>
    <td class="primera_fila">Mensaje</td>
    <td class="sin">&nbsp;</td>
    <td class="sin">&nbsp;</td>
    <td class="sin">&nbsp;</td>
  </tr>

  <?php
      foreach ($matrizAdmins as $admin):
  ?>
  <tr>
    <td><?php echo $admin["id"] ?></td>
    <td><?php echo $admin["nombre"] ?></td>
    <td><?php echo $admin["accion"] ?></td>
    <td><?php echo $admin["email"] ?></td>
    <td><?php echo $admin["password"] ?></td>
    <td><?php echo $admin["puerto"] ?></td>
    <td><?php echo $admin["host"] ?></td>
    <td><?php echo $admin["asunto"] ?></td>
    <td><?php echo $admin["mensaje"] ?></td>

    <td class="bot"><a href="servidor.php?borrar=<?php echo $admin["id"]?>">
      <input type='button' name='del' id='del' value='Borrar'></a></td>
    <td class='bot'>
      <a href='servidor.php?actualizar&id=<?php echo $admin["id"]?>&
nombre=<?php echo $admin["nombre"]?>&accion=<?php echo $admin["accion"]?>&
email=<?php echo $admin["email"]?>&password=<?php echo $admin["password"]?>&
puerto=<?php echo $admin["puerto"]?>&host=<?php echo $admin["host"]?>&
asunto=<?php echo $admin["asunto"]?>&mensaje=<?php echo $admin["mensaje"]?>'>
      <input type='button' name='up' class="up" value='Actualizar'></a></td>
  </tr>
  <?php
      endforeach;
  ?>

  <tr>
    <td></td>
    <td><input type='text' name='nombre' size='5' class='centrado' required></td>
    <td><input type='text' name='accion' size='5' class='centrado' required></td>
    <td><input type='email' name='email' size='5' class='centrado' required></td>
    <td><input type='text' name='password' size='5' class='centrado' required></td>
    <td><input type='number' name='puerto' size='3' class='centrado' required></td>
    <td><input type='text' name='host' size='5' class='centrado' required></td>
    <td><input type='text' name='asunto' size='5' class='centrado' required></td>
    <td><input type='text' name='mensaje' size='5' class='centrado' required></td>
    <td class='bot'><input type='submit' name='crear' id='crear' value='Insertar'></td>
  </tr>
    <!--              PAGINACIÓN -------------->
   <tr>
       <td colspan="3">
           <?php
           $pag = $consulta->paginacion();
           $total_paginas = $pag['total'];
           for ($i=1; $i <= $total_paginas; $i++) {
               echo "<a href='servidor.php?pagina=" . $i . "'>" . $i . "</a> ";
           }
           ?>
       </td>
   </tr>
</table>

</form>
