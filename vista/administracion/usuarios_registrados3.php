<!DOCTYPE html>
<html>
    <head>
        <?php

            session_start();
            if (!isset($_SESSION["usuario"])) {
                header("location:../index.php");
            }
        ?>
        <meta charset="utf-8">
        <title><?php echo "App-web: " . $_SESSION["usuario"];?></title>
    </head>
    <body>
        <h1 title="Mensaje"> Bienvenidos Usuarios</h1>
        <?php
            echo "USUARIO3: " . $_SESSION["usuario"] . "<br><br>" ;
        ?>
        <p><a href="cierre.php">Cierre Sesión</a></p>
        <p> Esto es información sólo para usuarios registrados</p>
        <p><a href="index.php">Volver</a></p>
    </body>
</html>
