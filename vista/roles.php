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
        obtenerRoles($mysqli);
        break;
    case "POST":
        agregarRol($mysqli);
        break;
    case "PUT":
        actualizarRol($mysqli);
        break;
    case "DELETE":
        eliminarRol($mysqli);
        break;
    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Error 405 "]);
}

function obtenerRoles($mysqli)
{
    $resultado = $mysqli->query("SELECT * FROM roles");
    $roles = $resultado->fetch_all(MYSQLI_ASSOC);
    // echo json_encode($roles);



    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Nombre</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';


    foreach ($roles as $rol) {
        echo '<tr>';
        echo '<td>' . $rol['id_rol'] . '</td>';
        echo '<td>' . $rol['nombre_rol'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}

function agregarRol($mysqli)
{
    $datos = json_decode(file_get_contents("php://input"), true);
    if (!isset($datos["nombre_rol"])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos"]);
        return;
    }

    $nombre_rol = $mysqli->real_escape_string($datos["nombre_rol"]);

    $sql = "INSERT INTO roles (nombre_rol) VALUES ('$nombre_rol')";
    if ($mysqli->query($sql)) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Rol '$nombre_rol' creado con éxito"]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "Error al crear rol '$nombre_rol'"]);
    }
}

function actualizarRol($mysqli)
{
    $datos = json_decode(file_get_contents("php://input"), true);
    if (!isset($datos["id_rol"], $datos["nombre_rol"])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos"]);
        return;
    }

    $id = $datos["id_rol"];
    $nombre_rol = $mysqli->real_escape_string($datos["nombre_rol"]);

    $sql = "UPDATE roles SET nombre_rol='$nombre_rol' WHERE id_rol=$id";
    if ($mysqli->query($sql)) {
        echo json_encode(["mensaje" => "Rol actualizado"]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "Error al actualizar"]);
    }
}

function eliminarRol($mysqli)
{
    parse_str(file_get_contents("php://input"), $datos);
    if (!isset($datos["id_rol"])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "ID de rol requerido"]);
        return;
    }

    $id = $datos["id_rol"];
    $sql = "DELETE FROM roles WHERE id_rol=$id";
    if ($mysqli->query($sql)) {
        echo json_encode(["mensaje" => "Rol eliminado"]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "Error al eliminar"]);
    }
}
