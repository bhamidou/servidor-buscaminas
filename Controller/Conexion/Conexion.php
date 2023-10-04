<?php

require_once 'Constantes.php';

class Conexion
{

    static $conexion;

    public function conectar()
    {
        try {
            self::$conexion = new mysqli(Constantes::$host, Constantes::$user, Constantes::$pass, Constantes::$database, Constantes::$port);
        } catch (Exception $e) {
            die();
        }
    }

    public function desconectar()
    {
        mysqli_close(self::$conexion);
    }
}
        