<?php


class Conexion
{
    private $host = "localhost";
    private $usuario = "root";
    private $password = "";
    private $baseDatos = "bd_prueba";
    private $puerto = "3306";
    public $conexion;

    public function __construct()
    {

        //se crea la conexión a la base de datos con las variables creadas anteriormente:
        $this->conexion = new mysqli($this->host, $this->usuario, $this->password, $this->baseDatos, $this->puerto);
        if ($this->conexion->connect_error) {

            die(json_encode(["error" => "Error en la conexion: " . $this->conexion->connect_error]));
        }

        $this->conexion->set_charset("utf8");
    }

    public function cerrarConexion()
    {
        $this->conexion->close();
    }
}
