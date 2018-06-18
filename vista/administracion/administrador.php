<!DOCTYPE html>
<html>
  <head>
    <?php
        session_start();
        if (!isset($_SESSION["administrador"])) {
            header("location:index.php");
        };
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
    <link rel="shortcut icon" type="img/x-icon" href="../img/app/ok_hand.png">
    <title><?php echo "Administrador: " . $_SESSION["administrador"];?></title>
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/administracion/administrador.css">
  </head>
  <body>
    <h1><a href="index.php"><img src="../img/app/ok_hand.png" alt="inicio" title="Inicio" id="logo"></a></h1>
    <h2>Bienvenido Administrador</h2>

    <?php
        echo "Hola: " . $_SESSION["administrador"] ;
        echo '   <a href="cierre.php"><i class="fa fa-power-off" aria-hidden="true"></i></a>';
        echo "<br><br>";
    ?>

    <table>
        <tr>
            <td colspan="3">ZONA ADMINISTRADORES REGISTRADOS</td>
        </tr>
        <tr>
            <td class="cen" title="correos"><a href="servidor.php">Añadir Mensaje</a></td>
            <td class="cen" title="página 2"><a href="dar_alta_profes.php">Da de alta a profesores</a></td>
            <td class="cen" title="página 3"><a href="usuarios_registrados3.php">PÁGINA 3</a></td>

        </tr>
    </table>
    <p>&nbsp;</p>
    <div id="barraaceptacion">
    	<div class="inner">
    		Solicitamos su permiso para obtener datos estadísticos de su navegación en esta web, en cumplimiento del Real Decreto-ley 13/2012. Si continúa navegando consideramos que acepta el uso de cookies.
    		<a href="javascript:void(0);" class="ok" onclick="PonerCookie();"><b>OK</b></a> |
    		<a href="http://politicadecookies.com" target="_blank" class="info">Más información</a>
    	</div>
    </div>
    <script src="../js/cookies.js" charset="utf-8"></script>
  </body>
</html>
