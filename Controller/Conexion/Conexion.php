<?php

require __DIR__.'../../../Constantes.php';


class Conexion
{

    public static $conexion;

    public function conectar()
    {
        try {
            self::$conexion = new mysqli(Constantes::$DB_host, Constantes::$DB_user, Constantes::$DB_pass, Constantes::$DB_database, Constantes::$DB_port);
        } catch (Exception $e) {
            die();
        }
    }

    public function desconectar()
    {
        mysqli_close(self::$conexion);
    }
}
