<?php


class controladorLogin
{
    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $datos_json = file_get_contents("php://input");
            $datos = json_decode($datos_json, true);

            if (isset($datos["correo"]) && isset($datos["contraseña"])) {
                require_once "modelo/bd.php";
                $conexion = new Conexion();
                $mysqli = $conexion->conexion;

                $correo = $mysqli->real_escape_string($datos["correo"]);
                $contraseña = $mysqli->real_escape_string($datos["contraseña"]);

                $resultado = $mysqli->query("SELECT * FROM usuarios WHERE correo='$correo'");

                if ($resultado->num_rows > 0) {
                    $usuario = $resultado->fetch_assoc();

                    // Verificación de contraseña
                    if ($contraseña == $usuario["contraseña"]) {
                        $_SESSION["usuario"] = $usuario["correo"];
                        echo json_encode([
                            "mensaje" => "Inicio de sesión exitoso",
                            "usuario" => $usuario["correo"],
                            "redirect" => "vista/inicio.php"
                        ]);
                    } else {
                        echo json_encode(["mensaje" => "Contraseña incorrecta"]);
                    }
                } else {
                    echo json_encode(["mensaje" => "Usuario no encontrado"]);
                }
            } else {
                echo json_encode(["mensaje" => "Datos incompletos"]);
            }
        } else {
            echo json_encode(["mensaje" => "Método no permitido"]);
        }
    }
}
