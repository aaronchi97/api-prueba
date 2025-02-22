<?php
session_start();


if (empty($_SESSION['usuario'])) {
    header("location:vista/login.html");
    exit();
}



header("Content-Type: text/html");
require_once "../modelo/bd.php";

$conexion = new Conexion();
$mysqli = $conexion->conexion;

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case "GET":
        obtenerModulos($mysqli);
        break;
    case "POST":
        agregarModulo($mysqli);
        break;
    case "PUT":
        actualizarModulo($mysqli);
        break;
    case "DELETE":
        eliminarModulo($mysqli);
        break;
    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Error 405 "]);
}

function obtenerModulos($mysqli)
{
    $resultado = $mysqli->query("SELECT * FROM modulos");
    $modulos = $resultado->fetch_all(MYSQLI_ASSOC);
    // echo json_encode($modulos);


    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Nombre</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';


    foreach ($modulos as $modulo) {
        echo '<tr>';
        echo '<td>' . $modulo['id_modulo'] . '</td>';
        echo '<td>' . $modulo['nombre_modulo'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}

function agregarModulo($mysqli)
{
    $datos = json_decode(file_get_contents("php://input"), true);
    if (!isset($datos["nombre_modulo"])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos"]);
        return;
    }

    $nombre_modulo = $mysqli->real_escape_string($datos["nombre_modulo"]);

    $sql = "INSERT INTO modulos (nombre_modulo) VALUES ('$nombre_modulo')";
    if ($mysqli->query($sql)) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Módulo '$nombre_modulo' creado con éxito"]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "Error al crear módulo '$nombre_modulo'"]);
    }
}

function actualizarModulo($mysqli)
{
    $datos = json_decode(file_get_contents("php://input"), true);
    if (!isset($datos["id_modulo"], $datos["nombre_modulo"])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos"]);
        return;
    }

    $id = $datos["id_modulo"];
    $nombre_modulo = $mysqli->real_escape_string($datos["nombre_modulo"]);

    $sql = "UPDATE modulos SET nombre_modulo='$nombre_modulo' WHERE id_modulo=$id";
    if ($mysqli->query($sql)) {
        echo json_encode(["mensaje" => "Módulo actualizado"]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "Error al actualizar"]);
    }
}

function eliminarModulo($mysqli)
{
    parse_str(file_get_contents("php://input"), $datos);
    if (!isset($datos["id_modulo"])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "ID de módulo requerido"]);
        return;
    }

    $id = $datos["id_modulo"];
    $sql = "DELETE FROM modulos WHERE id_modulo=$id";
    if ($mysqli->query($sql)) {
        echo json_encode(["mensaje" => "Módulo eliminado"]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "Error al eliminar"]);
    }
}
