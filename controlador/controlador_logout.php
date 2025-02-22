<?php
session_start();
session_unset(); // Eliminar todas las variables de sesión
session_destroy();


header("Location: ../index.php");
exit;
