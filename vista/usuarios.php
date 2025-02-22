<?php
session_start();

// Verificar si el usuario está logueado
if (empty($_SESSION['usuario'])) {
    header("location:vista/login.html"); // Redirigir al login si no está logueado
    exit();
}

header("Content-Type: text/html"); // Establecer el tipo de contenido como HTML
require_once "../modelo/bd.php";

$conexion = new Conexion();
$mysqli = $conexion->conexion;

$metodo = $_SERVER['REQUEST_METHOD'];

switch ($metodo) {
    case "GET":
        obtenerUsuarios($mysqli);
        break;
    case "POST":
        agregarUsuario($mysqli);
        break;
    case "PUT":
        actualizarUsuario($mysqli);
        break;
    case "DELETE":
        eliminarUsuario($mysqli);
        break;
    default:
        http_response_code(405);
        echo json_encode(["mensaje" => "Error 405 "]);
}

function obtenerUsuarios($mysqli)
{
    $resultado = $mysqli->query("SELECT * FROM usuarios");
    $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);


    echo '<table class="table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Nombre</th>';
    echo '<th>Correo</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';


    foreach ($usuarios as $usuario) {
        echo '<tr>';
        echo '<td>' . $usuario['id_usuario'] . '</td>';
        echo '<td>' . $usuario['nombre'] . '</td>';
        echo '<td>' . $usuario['correo'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}

function agregarUsuario($mysqli)
{
    $datos = json_decode(file_get_contents("php://input"), true);
    if (!isset($datos["nombre"], $datos["correo"], $datos["contraseña"])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos"]);
        return;
    }

    $nombre = $mysqli->real_escape_string($datos["nombre"]);
    $correo = $mysqli->real_escape_string($datos["correo"]);
    $contraseña = password_hash($datos["contraseña"], PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (nombre, correo, contraseña) VALUES ('$nombre', '$correo', '$contraseña')";
    if ($mysqli->query($sql)) {
        http_response_code(201);
        echo json_encode(["mensaje" => "Usuario '$nombre' creado con exito"]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "Error al crear usuario '$nombre'"]);
    }
}

function actualizarUsuario($mysqli)
{
    $datos = json_decode(file_get_contents("php://input"), true);
    if (!isset($datos["id_usuario"], $datos["nombre"], $datos["correo"])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "Datos incompletos"]);
        return;
    }
    $id = $datos["id_usuario"];
    $nombre = $mysqli->real_escape_string($datos["nombre"]);
    $correo = $mysqli->real_escape_string($datos["correo"]);

    $sql = "UPDATE usuarios SET nombre='$nombre', correo='$correo' WHERE id_usuario=$id";
    if ($mysqli->query($sql)) {
        echo json_encode(["mensaje" => "Usuario actualizado"]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "Error al actualizar"]);
    }
}

function eliminarUsuario($mysqli)
{
    parse_str(file_get_contents("php://input"), $datos);
    if (!isset($datos["id_usuario"])) {
        http_response_code(400);
        echo json_encode(["mensaje" => "ID de usuario requerido"]);
        return;
    }
    $id = $datos["id_usuario"];
    $sql = "DELETE FROM usuarios WHERE id_usuario=$id";
    if ($mysqli->query($sql)) {
        echo json_encode(["mensaje" => "Usuario eliminado"]);
    } else {
        http_response_code(500);
        echo json_encode(["mensaje" => "Error al eliminar"]);
    }
}
