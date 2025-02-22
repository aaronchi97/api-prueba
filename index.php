

<?php
session_start();

require_once "controlador/controlador_login.php";

$controladorLogin = new controladorLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controladorLogin->login();
} else {
    require_once "vista/login.php";
}
