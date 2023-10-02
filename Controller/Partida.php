<?php

require_once 'Constantes.php';
require_once 'Conexion.php';

class Partida{

    public function getPartida(){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM PARTIDA";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);

        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);
        $con->desconectar();

        $rtn= [];

        while ($fila = mysqli_fetch_row($resultados)) {
               $rtn[] = $fila[0].",". $fila[1].",".$fila[2].",".$fila[3];
        }

        print_r($rtn);
    }

    public function updatePartida($posicion){
        $con = new Conexion();
        $con->conectar();

        $con->desconectar();
    }
    
    public function insertPosPartida(){
        $con = new Conexion();
        $con->conectar();

        $con->desconectar();
    }

    public function getTableroInvisible($idUser){
        $con = new Conexion();
        $con->conectar();

        $consulta = "SELECT * FROM PARTIDA WHERE idUsuario = ? ";

        $stmt = mysqli_prepare(Conexion::$conexion, $consulta);
        mysqli_stmt_bind_param($stmt, "s", $idUser);
        mysqli_stmt_execute($stmt);
        $resultados = mysqli_stmt_get_result($stmt);
        $con->desconectar();

        $rtn= [];

        while ($fila = mysqli_fetch_row($resultados)) {
               $rtn[] = $fila[3];
        }

        print_r($rtn);
    }

}