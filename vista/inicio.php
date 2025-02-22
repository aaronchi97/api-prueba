<?php

session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css" />
    <title>Inicio</title>
</head>

<body>
    <h1>Bienvenido, <?php echo $_SESSION["usuario"]; ?></h1>

    <section class="btns"></section>
    <a class="btn" href="usuarios.php">Usuarios</a>
    <a class="btn" href="modulos.php">Módulos</a>
    <a class="btn" href="roles.php">Roles</a>
    <br><br>
    <a href="../controlador/controlador_logout.php">Cerrar sesión</a>
</body>

</html>